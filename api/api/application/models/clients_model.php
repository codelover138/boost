<?php
class Clients_model extends CI_Model
{
    public $table = 'boost_clients';

    public function get_clients($identifier = null)
    {
        if(!is_null($identifier) && is_numeric($identifier)) :
            $this->db->where('id', $identifier);
        endif;

        $result = $this->db->get($this->table)->result();

        return $result;
    }

    public function create($post)
    {
        $result = array('bool'=>false);

        if(!empty($post)) :
            $create = $this->db->insert($this->table, $post);
            if($create) :
                $result['bool'] = true;
                $result['message'] = 'client successfully created';
            else :
                $result['message'] = 'could not create client';
            endif;
        else :
            $result['message'] = 'empty post';
        endif;

        return $result;
    }

    public function update($id, $post)
    {
        $result = array('bool'=>false);

        if(!empty($post) && !empty($id)) :

            $this->db->where('id', $id);

            $run = $this->db->update($this->table, $post);
            if($run) :
                $result['bool'] = true;
                $result['message'] = 'client successfully updated';
            else :
                $result['message'] = 'could not update client information';
            endif;
        else :
            $result['message'] = 'empty post or id';
        endif;

        return $result;
    }

    public function delete($id)
    {
        $result = array('bool'=>false);

        if(!empty($id)) :
            $this->db->where('id', $id);
            $run = $this->db->delete($this->table);

            if($run) :
                $result['bool'] = true;
                $result['message'] = 'client successfully deleted';
            else :
                $result['message'] = 'could not delete client';
            endif;
        else :
            $result['message'] = 'empty id';
        endif;

        return $result;
    }
}