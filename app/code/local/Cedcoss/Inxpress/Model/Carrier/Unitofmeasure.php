<?php 
class Cedcoss_Inxpress_Model_Carrier_Unitofmeasure
{
    /**
     * Returns array to be used in multiselect on back-end
     *
     * @return array
     */
    public function toOptionArray()
    {
        $returnArr=array(array('value' => 'L', 'label' => 'Pounds'),array('value' => 'K', 'label' => 'Kilogram'));
        					
        return $returnArr;
    }
}