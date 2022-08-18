<?php
class Clients
{
    /**
     *
     */
    public function __construct()
    {
    }

    /**
     *
     */
    public function __destruct()
    {
    }
    
    /**
     * Set friendly columns\' names to order tables\' entries
     */
    public function setOrderingValues()
    {
        $ordering = [
            'MatriculeF' => 'الرقم التعريفي ',
            'NomArF' => 'الاسم',
            'PrenomArF' => 'اللقب',
        ];

        return $ordering;
    }
}
?>
