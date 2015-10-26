<?php


class NetGearWpPostModel
{
    private $ID;
    private $post_author;
    private $post_date;
    private $post_date_gmt;
    private $post_content;
    private $post_title;
    private $post_excerpt;
    private $post_status;
    private $comment_status;
    private $ping_status;
    private $post_password;
    private $post_name;
    private $to_ping;
    private $pinged;
    private $post_modified;
    private $post_modified_gmt;
    private $post_content_filtered;
    private $post_parent;
    private $guid;
    private $menu_order;
    private $post_type;
    private $post_mime_type;
    private $comment_count;

    /**
     * @return mixed
     */
    public function getID()
    {
        return $this->ID;
    }

    /**
     * @param mixed $ID
     * @return NetGearWpPostModel
     */
    public function setID($ID)
    {
        $this->ID = $ID;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPostAuthor()
    {
        return $this->post_author;
    }

    /**
     * @param mixed $post_author
     * @return NetGearWpPostModel
     */
    public function setPostAuthor($post_author)
    {
        $this->post_author = $post_author;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPostDate()
    {
        return $this->post_date;
    }

    /**
     * @param mixed $post_date
     * @return NetGearWpPostModel
     */
    public function setPostDate($post_date)
    {
        $this->post_date = $post_date;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPostDateGmt()
    {
        return $this->post_date_gmt;
    }

    /**
     * @param mixed $post_date_gmt
     * @return NetGearWpPostModel
     */
    public function setPostDateGmt($post_date_gmt)
    {
        $this->post_date_gmt = $post_date_gmt;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPostContent()
    {
        return $this->post_content;
    }

    /**
     * @param mixed $post_content
     * @return NetGearWpPostModel
     */
    public function setPostContent($post_content)
    {
        $this->post_content = $post_content;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPostTitle()
    {
        return $this->post_title;
    }

    /**
     * @param mixed $post_title
     * @return NetGearWpPostModel
     */
    public function setPostTitle($post_title)
    {
        $this->post_title = $post_title;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPostExcerpt()
    {
        return $this->post_excerpt;
    }

    /**
     * @param mixed $post_excerpt
     * @return NetGearWpPostModel
     */
    public function setPostExcerpt($post_excerpt)
    {
        $this->post_excerpt = $post_excerpt;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPostStatus()
    {
        return $this->post_status;
    }

    /**
     * @param mixed $post_status
     * @return NetGearWpPostModel
     */
    public function setPostStatus($post_status)
    {
        $this->post_status = $post_status;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCommentStatus()
    {
        return $this->comment_status;
    }

    /**
     * @param mixed $comment_status
     * @return NetGearWpPostModel
     */
    public function setCommentStatus($comment_status)
    {
        $this->comment_status = $comment_status;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPingStatus()
    {
        return $this->ping_status;
    }

    /**
     * @param mixed $ping_status
     * @return NetGearWpPostModel
     */
    public function setPingStatus($ping_status)
    {
        $this->ping_status = $ping_status;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPostPassword()
    {
        return $this->post_password;
    }

    /**
     * @param mixed $post_password
     * @return NetGearWpPostModel
     */
    public function setPostPassword($post_password)
    {
        $this->post_password = $post_password;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPostName()
    {
        return $this->post_name;
    }

    /**
     * @param mixed $post_name
     * @return NetGearWpPostModel
     */
    public function setPostName($post_name)
    {
        $this->post_name = $post_name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getToPing()
    {
        return $this->to_ping;
    }

    /**
     * @param mixed $to_ping
     * @return NetGearWpPostModel
     */
    public function setToPing($to_ping)
    {
        $this->to_ping = $to_ping;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPinged()
    {
        return $this->pinged;
    }

    /**
     * @param mixed $pinged
     * @return NetGearWpPostModel
     */
    public function setPinged($pinged)
    {
        $this->pinged = $pinged;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPostModified()
    {
        return $this->post_modified;
    }

    /**
     * @param mixed $post_modified
     * @return NetGearWpPostModel
     */
    public function setPostModified($post_modified)
    {
        $this->post_modified = $post_modified;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPostModifiedGmt()
    {
        return $this->post_modified_gmt;
    }

    /**
     * @param mixed $post_modified_gmt
     * @return NetGearWpPostModel
     */
    public function setPostModifiedGmt($post_modified_gmt)
    {
        $this->post_modified_gmt = $post_modified_gmt;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPostContentFiltered()
    {
        return $this->post_content_filtered;
    }

    /**
     * @param mixed $post_content_filtered
     * @return NetGearWpPostModel
     */
    public function setPostContentFiltered($post_content_filtered)
    {
        $this->post_content_filtered = $post_content_filtered;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPostParent()
    {
        return $this->post_parent;
    }

    /**
     * @param mixed $post_parent
     * @return NetGearWpPostModel
     */
    public function setPostParent($post_parent)
    {
        $this->post_parent = $post_parent;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getGuid()
    {
        return $this->guid;
    }

    /**
     * @param mixed $guid
     * @return NetGearWpPostModel
     */
    public function setGuid($guid)
    {
        $this->guid = $guid;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMenuOrder()
    {
        return $this->menu_order;
    }

    /**
     * @param mixed $menu_order
     * @return NetGearWpPostModel
     */
    public function setMenuOrder($menu_order)
    {
        $this->menu_order = $menu_order;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPostType()
    {
        return $this->post_type;
    }

    /**
     * @param mixed $post_type
     * @return NetGearWpPostModel
     */
    public function setPostType($post_type)
    {
        $this->post_type = $post_type;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPostMimeType()
    {
        return $this->post_mime_type;
    }

    /**
     * @param mixed $post_mime_type
     * @return NetGearWpPostModel
     */
    public function setPostMimeType($post_mime_type)
    {
        $this->post_mime_type = $post_mime_type;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCommentCount()
    {
        return $this->comment_count;
    }

    /**
     * @param mixed $comment_count
     * @return NetGearWpPostModel
     */
    public function setCommentCount($comment_count)
    {
        $this->comment_count = $comment_count;
        return $this;
    }


}