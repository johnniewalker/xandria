<?php

namespace Xandria;
/**
 * @desc Handle that is responsible for triggering E_USER family of errors.
 * Removes need for client code to pass line numbers etc at the the place where they tirgger the error.
 */
class MyErrorTrigger
{
    //Toggle depending on state of installation.
    static protected $weDoNotExpectDbToBeWorking = FALSE;


    /**
     * @desc Decorates error message with debug_backtrace information and escapes htmlchars.
     *
     * @return The return value of the trigger_error() func that it delegates to.
     */
    static public function triggerMyError($message, $level)
    {
        //Get the caller of the calling function and details about it
        $backtraceArray = debug_backtrace();
        $calleeArray = $backtraceArray[1];


        //Trigger appropriate error
        return trigger_error(
            '(user generated) ' .
            htmlspecialchars($message)
            . self::renderCalleeArray($calleeArray)
            , $level
        );
    }


    protected function renderCalleeArray($calleeArray)
    {
        return ' in '
            . htmlspecialchars($calleeArray['file']) . ' on line '
            . htmlspecialchars($calleeArray['line']);
    }

    /**
     * @desc Custom wrapper to conditionally throw a consistent warning message
     * when we are Gracefully Handling a Db Connenction Errors
     *
     * 'conditionally' in the sense that we can toggle whether or
     * not such errors are suppressed (for circumstances when we are
     * testing a semi-installed app which we know does not have a db connection )
     *
     * @return void
     */
    static public function triggerGracefulDbDegradationWarning()
    {


        if (self::$weDoNotExpectDbToBeWorking) {
            //Do NOT trigger the error. Maintain silence.
            return;
        }
        //else
        MyErrorTrigger::triggerMyError('Gracefully handling db connection error', E_USER_WARNING);
    }


    /**
     * @desc Custom wrapper to throw a consistent warning message
     * when we are Gracefully Handling Config Errors
     *
     * @return void
     */
    static public function triggerGracefulSystemMisconfigurationWarning($message)
    {
        //else
        MyErrorTrigger::triggerMyError('Gracefully handled config error - ' . $message, E_USER_WARNING);
    }


    /**
     * @desc Custom wrapper to throw a consistent message
     * when we are throwing an error because an assertion failed.
     *
     * @return void
     */
    static public function triggerAssertionFailedError($message)
    {
        //else
        MyErrorTrigger::triggerMyError(
            'Assertion failed error - ' . $message,
            E_USER_ERROR
        );
    }


    /**
     * @desc Custom wrapper to throw a consistent warning message when some peice of userland data is undefined.
     *
     * The idea for this method is inspired by the kinds
     * of e notices that php throws for undefined variables, etc.
     * except this is for bugs in *our* data as opposed to *php* data.
     *
     * Userland = data that is external to the software programme.
     * i.e taxonomy data, Uquims config, data provided by the user, etc
     *
     * @return void
     */
    static public function triggerUserlandDataIsUndefinedWarning($message)
    {
        //else
        MyErrorTrigger::triggerMyError('Some userland data is undefined - ' . $message, E_USER_WARNING);
    }


    /**
     * @desc Designed specifically to handle errors triggered by Xandria_Db_SQLException (with relevant backtrace info)
     * @return void
     */
    static public function triggerDbSQLExceptionWarning(
        \Xandria\Db\SQLException $sQLExceptionObj
    )
    {

        if (self::$weDoNotExpectDbToBeWorking) {
            //Do NOT trigger the error. Maintain silence.
            return;
        }


        //triggerMyError ensuring the backtrace info specifies the invoker of the the excpetion and not the exception itself
        $backtraceArray = debug_backtrace();

        if (array_key_exists(4, $backtraceArray)) {
            $calleeArray = $backtraceArray[4];
            $traceInfo = self::renderCalleeArray($calleeArray);
        }

        $calleeArray = $backtraceArray[3];
        $traceInfo .= self::renderCalleeArray($calleeArray);

        $calleeArray = $backtraceArray[2];
        $traceInfo .= ' then in ' . self::renderCalleeArray($calleeArray);

        //Should only be true on dev machine.
        if ((\Xandria\AppEnvironment\EnvironmentType::isEnvVarSetToATrueValue())
            && (!(\Xandria\AppEnvironment\EnvironmentType::doesEnvVarIndicateProductionEnvironment()))) {
            //We are in a specified environmnet
            // and NOT in a production environment.

            //Toggle this to display SQL error info.
            $showDangerousData = TRUE;
            if ($showDangerousData) {
                $traceInfo .= '<pre>' . $sQLExceptionObj->get_SQLerror_mssg() . '</pre>';
            }
        }

        /**
         * Log this to our Atomic Logs to record extra details.
         *
         * To view or delete these atomic logs see: wgp_exsuite_tests.CodeAnalysis.AtomicLogsBrowser.
         */
        $atomicLogger = new \Xandria\Logs\AtomicLogInbox();
        $atomicLogFilename = $atomicLogger->storeMessageAsAtomicLogFile(
            'log-type: Xandria_Db_SQLException occured' . PHP_EOL
            . PHP_EOL
            . $sQLExceptionObj->get_SQLerror_mssg() . PHP_EOL
            . $traceInfo . PHP_EOL
        );

        //Trigger an appropriate error.
        trigger_error(
            '(user generated) ' .
            htmlspecialchars('Xandria_Db_SQLException occured (see logfile: ' . $atomicLogFilename . ')')
            . $traceInfo,
            E_USER_WARNING
        );
    }


}