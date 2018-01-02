<?php


class NetGearWpTermsModel
{
    private $term_id;
    private $name;
    private $slug;
    private $term_group;

    /**
     * @return mixed
     */
    public function getTermId()
    {
        return $this->term_id;
    }

    /**
     * @param mixed $term_id
     * @return NetGearWpTermsModel
     */
    public function setTermId($term_id)
    {
        $this->term_id = $term_id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return NetGearWpTermsModel
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param mixed $slug
     * @return NetGearWpTermsModel
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTermGroup()
    {
        return $this->term_group;
    }

    /**
     * @param mixed $term_group
     * @return NetGearWpTermsModel
     */
    public function setTermGroup($term_group)
    {
        $this->term_group = $term_group;
        return $this;
    }


}