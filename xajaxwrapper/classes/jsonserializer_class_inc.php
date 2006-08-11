<?php

class jsonserializer //extends object
{
    /**
     * How objects should be encoded -- arrays or as StdClass
     */
    const TYPE_ARRAY  = 0;
    const TYPE_OBJECT = 1;

    /**
     * Decodes the given $encodedValue string which is
     * encoded in the JSON format
     *
     * @param string $encodedValue Encoded in JSON format
     * @param int $objectDecodeType Optional; flag indicating how to decode
     * objects.
     * @return mixed
     */
    static public function decode($encodedValue, $objectDecodeType = null)
    {

        include_once 'jsondecoder_class_inc.php';
        return jsondecoder::decode($encodedValue, $objectDecodeType);
    }


    /**
     * Encode the mixed $valueToEncode into the JSON format
     *
     * NOTE: Object should not contain cycles; the JSON format
     * does not allow object reference.
     *
     * NOTE: Only public variables will be encoded
     *
     * @param mixed $valueToEncode
     * @return string JSON encoded object
     */
    static public function encode($valueToEncode)
    {

        include_once 'jsonencoder_class_inc.php';
    	return jsonencoder::encode($valueToEncode);
    }
}
?>