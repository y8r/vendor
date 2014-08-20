<?php
/**
 * Array Nested Value
 * 
 * Returns the value of a nested array with a variable depth
 * 
 * @author Jeff Yates <jeff@jeffyates.com>
 * @version 1.0.0
 * 
 * <code>
 *      // Example response array
 *      $response['test']['of']['this'] = 0;
 *      
 *      // Instantiate class
 *      $nestedValue = new ArrayNestedValue;
 *
 *      // Example usage
 *      $array       = $nestedValue->get(array('test', 'of', 'this'), $response);
 *      $string      = $nestedValue->get("[test]['of'][\"this\"]", $response);
 *      $altString   = $nestedValue->get("test,of,this", $response);
 *      $altString2  = $nestedValue->get("test->of->this", $response, "->");
 *      $failure     = $nestedValue->get("['test']['fail']", $response);
 *      $conditional = $nestedValue->get("['test']['of']['this']", $response) === 0;*
 *
 *      // Debug
 *      echo '<pre>'; var_dump($array, $string, $altString, $altString2, $failure, $conditional); echo '</pre>';
 * </code>
 *
 * @todo enable use of a json object
 */
class ArrayNestedValue
{
    public function __construct(){}
    
    public function __destruct(){}
    
    /**
     * Main Method
     * 
     * Detect needle type invoke the cooreleated method
     *
     * @param  mixed  $needle
     * @param  array  $haystack
     * @param  string $delimiter
     * @retrun mixed
     */
    public function get($needle, $haystack, $delimiter=',')
    {
        if (is_array($needle) === true) {
            return $this->valueByArray($needle, $haystack);
        } else {
            return $this->valueByString($needle, $haystack, $delimiter);
        }
    }
    
    /**
     * Value by Array notation
     * 
     * @param  array  $needle
     * @param  array  $haystack
     * @return mixed
     */
    protected function valueByArray($needle, $haystack)
    {
        $return = &$haystack;
        foreach ($needle as $key) {
            if (array_key_exists($key, $return) === true) {
                $return = &$return[$key];   
            } else {
                return false;
            }
        }
        
        return $return;
    }
    
    /**
     * Value by String notation
     * 
     * @param  string  $needle
     * @param  array   $haystack
     * @param  string  $delimiter
     * @return mixed
     */
    protected function valueByString($needle, $haystack, $delimiter=',')
    {
        $needle = explode($delimiter,
            preg_replace(
                array(
                    '/\[[\'\"]?/',
                    '/[\'\"]?\]/',
                    sprintf('/%s$/', $delimiter)
                ), 
                array(
                    '',
                    $delimiter,
                    ''
                ),
                $needle
            )
        );
        
        return $this->valueByArray($needle, $haystack);
    }
}
