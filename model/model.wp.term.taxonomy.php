<?php


class NetGearWpTermTaxonomy
{
    private $term_taxonomy_id;
    private $term_id;
    private $taxonomy;
    private $description;
    private $parent;
    private $count;

    /**
     * @return mixed
     */
    public function getTermTaxonomyId()
    {
        return $this->term_taxonomy_id;
    }

    /**
     * @param mixed $term_taxonomy_id
     * @return NetGearWpTermTaxonomy
     */
    public function setTermTaxonomyId($term_taxonomy_id)
    {
        $this->term_taxonomy_id = $term_taxonomy_id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTermId()
    {
        return $this->term_id;
    }

    /**
     * @param mixed $term_id
     * @return NetGearWpTermTaxonomy
     */
    public function setTermId($term_id)
    {
        $this->term_id = $term_id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTaxonomy()
    {
        return $this->taxonomy;
    }

    /**
     * @param mixed $taxonomy
     * @return NetGearWpTermTaxonomy
     */
    public function setTaxonomy($taxonomy)
    {
        $this->taxonomy = $taxonomy;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     * @return NetGearWpTermTaxonomy
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param mixed $parent
     * @return NetGearWpTermTaxonomy
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * @param mixed $count
     * @return NetGearWpTermTaxonomy
     */
    public function setCount($count)
    {
        $this->count = $count;
        return $this;
    }


}