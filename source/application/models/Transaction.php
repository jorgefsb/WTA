<?php

class Application_Model_Transaction extends Core_Model {

    protected $_tableTransaction;
    protected $_tableTransactionState;
    protected $_tableTransactionDetails;
    protected $_tableProduct;
    protected $_tableMember;

    public function __construct() {
        $this->_tableTransaction = new Application_Model_DbTable_Transaction();
        $this->_tableTransactionState = new Application_Model_DbTable_TransactionState();
        $this->_tableTransactionDetails = new Application_Model_DbTable_TransactionDetails();
        $this->_tableProduct = new Application_Model_DbTable_Product();
        $this->_tableMemeber = new Application_Model_DbTable_Member();
    }

    /**
     * metodo getTransaction(), devuelve todos los datos de un Transaction
     * @param $idTransaction    id de la Transaction  
     * @return array            devuelve un array asociativo con las columnas y su respectivo valor    
     */
    function getTransaction($idTransaction) {
        $smt = $this->_tableTransaction->select()
                ->where('transaction_id =?', $idTransaction)
                ->query();
        $result = $smt->fetch();
        $smt->closeCursor();
        return $result;
    }

    /**
     * metodo insert(), registra los datos de la Transaction 
     * @param array             $data   array con los datos de la Transaction array('column'=>'valor')
     * @return bolean or int    devuelve un entero en caso de que el registro sea exitos        
     */
    public function listing() {
        $smt = $this->_tableTransaction
                ->select()
                ->query();
        $result = $smt->fetchAll();
        $smt->closeCursor();
        return $result;
    }

    /**
     * metodo insert(), registra los datos de la Transaction 
     * @param array             $data   array con los datos de la Transaction array('column'=>'valor')
     * @return bolean or int    devuelve un entero en caso de que el registro sea exitos        
     */
    public function insert($data) {
        if ($this->_tableTransaction->insert($data)) {
            return $this->_tableTransaction->getAdapter()->lastInsertId();
        } else {
            return false;
        }
    }

    /**
     * metodo update(), registra los datos de la Transaction 
     * @param array     $data           array con los datos de la Transaction array('column'=>'valor')
     * @param int       $idTransaction  id de la Transaction
     * @return bolean   
     */
    public function update($data, $idTransaction) {
        if ($idTransaction != '') {
            $where = $this->_tableTransaction->getAdapter()
                    ->quoteInto('transaction_id =?', $idTransaction);
            return $this->_tableTransaction->update($data, $where);
        } else {
            return false;
        }
    }

    function listOrdens($filtro=array()) {
        $smt = $this->_tableTransaction
                ->getAdapter()
                ->select()
                ->from(array('tr' => $this->_tableTransaction->getName()), array(
                    'tr.transaction_payment_date',
                    'tr.transaction_amount',
                    'tr.transaction_user_menbership',
                    'tr.member_id',
                    'mem.member_name',
                    'mem.member_last_name',
                    'tr.transaction_amount',
                    'tr.transaction_register_date',
                    'tr.tansaction_state_id',
                    'trs.tansaction_state_name',
                    'tr.transaction_delivered',
                    'tr.transaction_delivered_date',
                    'product' => new Zend_Db_Expr("GROUP_CONCAT(concat(pr.product_name,'|',trd.transaction_details_product_cant,'|',trd.transaction_details_product_price,'|',trd.transaction_details_product_price_member,'|',coalesce(trd.product_size, ''),'|',coalesce(pr.product_code, '')) SEPARATOR '[]')"),
                ))
                ->join(array('trd' => $this->_tableTransactionDetails->getName()), 'trd.transaction_id=tr.transaction_id', '')
                ->join(array('trs' => $this->_tableTransactionState->getName()), 'trs.tansaction_state_id=tr.tansaction_state_id', '')
                ->join(array('pr' => $this->_tableProduct->getName()), 'pr.product_id=trd.product_id', '')
                ->join(array('mem' => $this->_tableMemeber->getName()), 'mem.member_id=tr.member_id', '')
                ->group('tr.transaction_id');
        foreach($filtro as $index=>$value){
            switch ($index) {
                case 'fromDate':
                     $smt = $smt->where('transaction_register_date >=?',$value);
                    break;
                case 'toDate':
                    $smt = $smt->where('transaction_register_date <=?',$value);
                    break;
                case 'menbers':
                    $smt = $smt->where('tr.member_id in ('.implode(',',$value).')');
                    break;
                case 'state':
                    $smt = $smt->where('tr.tansaction_state_id in ('.implode(',',$value).')');
                    break;
                case 'stateDelivered':
                    $smt = $smt->where('tr.transaction_delivered in ('.implode(',',$value).')');
                    break;
            }
        }
        $smt = $smt->query();
        $result = $smt->fetchAll();
        $smt->closeCursor();
        return $result;
    }

    function listOrdensUsers() {
        $smt = $this->_tableTransaction
                ->getAdapter()
                ->select()
                ->from(array('tr' => $this->_tableTransaction->getName()), array(
                    'tr.member_id',
                    'mem.member_name',
                    'mem.member_last_name'))
                ->join(array('trd' => $this->_tableTransactionDetails->getName()), 'trd.transaction_id=tr.transaction_id', '')
                ->join(array('trs' => $this->_tableTransactionState->getName()), 'trs.tansaction_state_id=tr.tansaction_state_id', '')
                ->join(array('pr' => $this->_tableProduct->getName()), 'pr.product_id=trd.product_id', '')
                ->join(array('mem' => $this->_tableMemeber->getName()), 'mem.member_id=tr.member_id', '')
                ->distinct()
                ->query();
        $result = $smt->fetchAll();
        $smt->closeCursor();
        return $result;
    }

}

