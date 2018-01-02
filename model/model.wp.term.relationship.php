<?php


class NetGearWpTermRelationship
{
    private $object_id;
    private $term_taxonomy_id;
    private $term_order;

    /**
     * @return mixed
     */
    public function getObjectId()
    {
        return $this->object_id;
    }

    /**
     * @param mixed $object_id
     * @return NetGearWpTermRelationship
     */
    public function setObjectId($object_id)
    {
        $this->object_id = $object_id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTermTaxonomyId()
    {
        return $this->term_taxonomy_id;
    }

    /**
     * @param mixed $term_taxonomy_id
     * @return NetGearWpTermRelationship
     */
    public function setTermTaxonomyId($term_taxonomy_id)
    {
        $this->term_taxonomy_id = $term_taxonomy_id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTermOrder()
    {
        return $this->term_order;
    }

    /**
     * @param mixed $term_order
     * @return NetGearWpTermRelationship
     */
    public function setTermOrder($term_order)
    {
        $this->term_order = $term_order;
        return $this;
    }


}