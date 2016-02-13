<?php

class Transactions extends MY_Model2
{
    // constructor
    function __construct()
    {
        parent::__construct('transactions', 'Stock', 'Player');
    }

    function displayTransactions($code){
        return $this->group($code);
    }

    function getAllTransactions()
    {
        $transactions = $this->all();

        /* Add additional attributes to each Stock */
        foreach ($transactions as $transaction)
        {
            // Add a link to each stock's history page
            $transaction->href = '/transaction/' . $transaction->DateTime;
        }

        return $transactions;

    }

    public function getPlayerTransactions($player){

        $this->db->select('*');
        $this->db->from('transactions t');
        $this->db->where('t.Player', $player);
        $query = $this->db->get();
        $noData = array();
        $noPlayer = [
            "DateTime" => "No Data",
            "Player" => "No Data",
            "Stock" => "No Data",
            "Trans" => "No Data",
            "Quantity" => "No Data",
        ];
        array_push($noData, $noPlayer);

        if($query->num_rows() != 0)
        {
            $resultset = $query->result_array();
        }else{
            $resultset = $noData;
        }

        return $resultset;
    }

    public function getCurrentHoldings($player){
        $resultset = null;

        $this->db->select('*');
        $this->db->from('transactions t');
        $this->db->join('stocks s', 's.Code=t.Stock', 'left');
        $this->db->join('players p', 'p.Player=t.Player', 'left');
        $this->db->where('t.Player', $player);
        $query = $this->db->get();
        if($query->num_rows() != 0)
        {
            $resultset = $query->result_array();
        }
        $resultBond = 0;
        $resultGold = 0;
        $resultGrain = 0;
        $resultInd = 0;
        $resultOil = 0;
        $resultTech = 0;
        $resultArray = Array();



        if($resultset != null)
        {
            foreach($resultset as $key => &$value)
            {

                if($value['Stock'] == "BOND") {
                    if ($value['Trans'] == 'buy')
                        $resultBond += $value['Quantity'];
                    elseif ($value['Trans'] == 'sell')
                        $resultBond -= $value['Quantity'];
                } else if($value['Stock'] == "GOLD") {
                    if ($value['Trans'] == 'buy')
                        $resultGold += $value['Quantity'];
                    elseif ($value['Trans'] == 'sell')
                        $resultGold -= $value['Quantity'];
                } else if($value['Stock'] == "GRAN") {
                    if ($value['Trans'] == 'buy')
                        $resultGrain += $value['Quantity'];
                    elseif ($value['Trans'] == 'sell')
                        $resultGrain -= $value['Quantity'];
                }else if($value['Stock'] == "IND") {
                    if ($value['Trans'] == 'buy')
                        $resultInd += $value['Quantity'];
                    elseif ($value['Trans'] == 'sell')
                        $resultInd -= $value['Quantity'];
                }else if($value['Stock'] == "OIL") {
                    if ($value['Trans'] == 'buy')
                        $resultOil += $value['Quantity'];
                    elseif ($value['Trans'] == 'sell')
                        $resultOil -= $value['Quantity'];
                }else if($value['Stock'] == "TECH") {
                    if ($value['Trans'] == 'buy')
                        $resultTech += $value['Quantity'];
                    elseif ($value['Trans'] == 'sell')
                        $resultTech -= $value['Quantity'];
                }
            }

        }

        $resultArray = [
            "BOND" => $resultBond,
            "GOLD" => $resultGold,
            "GRAN" => $resultGrain,
            "IND" => $resultInd,
            "OIL" => $resultOil,
            "TECH" => $resultTech
        ];


        return $resultArray;
    }

}