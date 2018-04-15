<?php
namespace Xandria\Db;

/**
*@desc a set of services that tests desc array and allow retrival of messages after each check
*/
class TblDescArrayChecker
	{
	protected $_messages = array();


	public function isDescArrayElementUsable( $tbl_desc_array, $desc_array_element )
        {
		//clear the messages
		$this->_messages=array();

		$desc_array_element = trim( $desc_array_element );

	    if (!( $tbl_desc_array ))
	            {
	            //the $tbl_desc_array is null
	            $this->_messages[] = '$tbl_desc_array[$desc_array_element] Error: the $tbl_desc_array is null';

	            return FALSE;
	            }


	    if (!( is_array( $tbl_desc_array ) ))
	            {
	            //the $tbl_desc_array is not an array

	            $this->_messages[] = '$tbl_desc_array[$desc_array_element] Error: the $tbl_desc_array is not an array';

	            return FALSE;
	            }

	    if (!( $desc_array_element ))
	            {
	            //the element variable doesnt exist

	            $this->_messages[] = '$tbl_desc_array[$desc_array_element] Error: the element variable doesnt exist';

	            return FALSE;
	            }

	    if (!( isset( $tbl_desc_array[$desc_array_element] ) ))
	            {
	            //the $tbl_desc_array is not set

	            $this->_messages[]='$tbl_desc_array[$desc_array_element] Error: the element is not set on the $tbl_desc_array';
				$this->_messages[]='-> $desc_array_element:'. $desc_array_element;

	            return FALSE;
	            }


		/**
		* @desc i'm not sure why we don't do:
		* if (!( strlen( trim( $tbl_desc_array[$desc_array_element] ) ) > 0 ))
		*/
	    if (!( $tbl_desc_array[$desc_array_element] ))		
			{
			//the element holds an empty string or doesnt exist

			$this->_messages[]='$tbl_desc_array[$desc_array_element] Error: the element holds an empty string or doesnt exist';
			$this->_messages[]='-> $desc_array_element:'. $desc_array_element;

			return FALSE;
			}


	    //else
	    return TRUE;
	    }

	/**
	*@desc
	* @throws \Exception if fails
	*/
	public function checkCriticalDescArrayElementsArray(
				$tbl_desc_array,
				$critical_desc_array_elements_array 
				)
		{
		reset($critical_desc_array_elements_array);

		while(list($key,$value) = each($critical_desc_array_elements_array))
		        {
		        //iterates through array of critical elements testing the desc array to see if any are missing


		        if (!( $this->isDescArrayElementUsable( $tbl_desc_array, $value ) ))
		                {
		                //fatal error
		                //error
		                //echo();
						throw new \Exception( 'Error: $tbl_desc_array[' . $value . '] is unusable' );
		                }
		        }

		return TRUE;
		}


	public function getMessages()
		{
		return $this->_messages;
		}
	}