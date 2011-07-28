<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 *  Codeigniter Tags Library
 *  Based on Freetag class. Original license info below.
 *  Ported by Mustafa Navruz
 *
 *  @property CI_DB_active_record $db
 */
/**
 *  Gordon Luk's Tags - Generalized Open Source Tagging and Folksonomy.
 *  Copyright (C) 2004-2006 Gordon D. Luk <gluk AT getluky DOT net>
 *
 *  Released under both BSD license and Lesser GPL library license.  Whenever
 *  there is any discrepancy between the two licenses, the BSD license will
 *  take precedence. See License.txt.
 *
 *  Freetag API Implementation
 *
 *  Freetag is a generic PHP class that can hook-in to existing database
 *  schemas and allows tagging of content within a social website. It's fun,
 *  fast, and easy!  Try it today and see what all the folksonomy fuss is
 *  about.
 *
 *  Contributions and/or donations are welcome.
 *
 *  Author: Gordon Luk
 *  http://www.getluky.net
 *
 *  Version: 0.260
 *  Last Updated: Jun 4, 2006
 *
 */

/**
 * class sql
 *
 *
  CREATE TABLE ci_tags (
  id int(10) unsigned NOT NULL auto_increment,
  tag varchar(50) NOT NULL default '',
  raw_tag varchar(50) NOT NULL default '',
  PRIMARY KEY  (id)
  ) TYPE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

  --
  -- Table structure for table `Tagsged_objects`
  --

  CREATE TABLE ci_tags_object (
  tag_id int(10) unsigned NOT NULL default '0',
  tagger_id int(10) unsigned NOT NULL default '0',
  object_id int(10) unsigned NOT NULL default '0',
  `tagged_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY  (`tag_id`,`tagger_id`,`object_id`),
  KEY `tag_id_index` (`tag_id`),
  KEY `tagger_id_index` (`tagger_id`),
  KEY `object_id_index` (`object_id`)
  ) TYPE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
 *
 */
class Tags {

    private $_MAX_TAG_LENGTH = 50;
    private $ci;

    CONST TABLE_TAGS = 'tags';
    CONST TABLE_OBJ = 'tags_object';

    function __construct()
    {
        $this->ci = & get_instance();
        log_message('debug', "Tags Class Initialized");
    }

    /**
     * get_objects_with_tag
     *
     * Use this function to build a page of results that have been tagged with the same tag.
     * Pass along a tagger_id to collect only a certain user's tagged objects, and pass along
     * none in order to get back all user-tagged objects. Most of the get_*_tag* functions
     * operate on the normalized form of tags, because most interfaces for navigating tags
     * should use normal form.
     *
     * @param string - Pass the normalized tag form along to the function.
     * @param int (Optional) - The numerical offset to begin display at. Defaults to 0.
     * @param int (Optional) - The number of results per page to show. Defaults to 100.
     * @param int (Optional) - The unique ID of the 'user' who tagged the object.
     *
     * @return An array of Object ID numbers that reference your original objects.
     */
    function get_objects_with_tag($tag, $offset = 0, $limit = 100, $tagger_id = NULL)
    {
        if (!isset($tag))
            return FALSE;

        $this->ci->db->distinct();
        $this->ci->db->select('object_id');
        $this->ci->db->from(self::TABLE_OBJ);
        $this->ci->db->join(self::TABLE_TAGS, 'tag_id = id', 'inner');
        $this->ci->db->where('tag', $tag);
        if (isset($tagger_id) && ($tagger_id > 0))
            $this->ci->db->where('tagger_id', $tagger_id);
        $this->ci->db->order_by('object_id');
        $this->ci->db->limit($limit, $offset);
        $query = $this->ci->db->get();
        $retarr = array();
        foreach ($query->result_array() as $row)
        {
            $retarr[] = $row['object_id'];
        }
        return $retarr;
    }

    /**
     * get_objects_with_tag_all
     *
     * Use this function to build a page of results that have been tagged with the same tag.
     * This function acts the same as get_objects_with_tag, except that it returns an unlimited
     * number of results. Therefore, it's more useful for internal displays, not for API's.
     * Pass along a tagger_id to collect only a certain user's tagged objects, and pass along
     * none in order to get back all user-tagged objects. Most of the get_*_tag* functions
     * operate on the normalized form of tags, because most interfaces for navigating tags
     * should use normal form.
     *
     * @param string - Pass the normalized tag form along to the function.
     * @param int (Optional) - The unique ID of the 'user' who tagged the object.
     *
     * @return An array of Object ID numbers that reference your original objects.
     */
    function get_objects_with_tag_all($tag, $tagger_id = NULL)
    {
        if (!isset($tag))
            return FALSE;

        $this->ci->db->distinct();
        $this->ci->db->select('object_id');
        $this->ci->db->from(self::TABLE_OBJ);
        $this->ci->db->join(self::TABLE_TAGS, 'tag_id = id', 'inner');
        $this->ci->db->where('tag', $tag);
        if (isset($tagger_id) && ($tagger_id > 0))
            $this->ci->db->where('tagger_id', $tagger_id);
        $this->ci->db->order_by('object_id');
        $query = $this->ci->db->get();
        $retarr = array();
        foreach ($query->result_array() as $row)
        {
            $retarr[] = $row['object_id'];
        }
        return $retarr;
    }

    /**
     * get_objects_with_tag_combo
     *
     * Returns an array of object ID's that have all the tags passed in the
     * tagArray parameter. Use this to provide tag combo services to your users.
     *
     * @param array - Pass an array of normalized form tags along to the function.
     * @param int (Optional) - The numerical offset to begin display at. Defaults to 0.
     * @param int (Optional) - The number of results per page to show. Defaults to 100.
     * @param int (Optional) - Restrict the result to objects tagged by a particular user.
     *
     * @return An array of Object ID numbers that reference your original objects.
     */
    function get_objects_with_tag_combo($tag_array, $offset = 0, $limit = 100, $tagger_id = NULL)
    {
        if (!isset($tag_array) || !is_array($tag_array))
            return FALSE;

        $retarr = array();
        if (count($tag_array) == 0)
            return $retarr;

        $tag_array = array_unique($tag_array);

        $this->ci->db->select(self::TABLE_OBJ . '.object_id, tag, COUNT(DISTINCT tag) AS uniques');
        $this->ci->db->from(self::TABLE_OBJ);
        $this->ci->db->join(self::TABLE_TAGS, self::TABLE_OBJ . '.tag_id = ' . self::TABLE_TAGS . '.id', 'inner');
        $this->ci->db->where_in(self::TABLE_TAGS . '.tag', $tag_array);
        if (isset($tagger_id) && ($tagger_id > 0))
            $this->ci->db->where('tagger_id', $tagger_id);
        $this->ci->db->group_by(self::TABLE_OBJ . '.object_id');
        $this->ci->db->having('uniques', count($tag_array));
        $this->ci->db->limit($limit, $offset);
        $query = $this->ci->db->get();
        foreach ($query->result_array() as $row)
        {
            $retarr[] = $row['object_id'];
        }
        return $retarr;
    }

    /**
     * get_objects_with_tag_id
     *
     * Use this function to build a page of results that have been tagged with the same tag.
     * This function acts the same as get_objects_with_tag, except that it accepts a numerical
     * tag_id instead of a text tag.
     * Pass along a tagger_id to collect only a certain user's tagged objects, and pass along
     * none in order to get back all user-tagged objects.
     *
     * @param int - Pass the ID number of the tag.
     * @param int (Optional) - The numerical offset to begin display at. Defaults to 0.
     * @param int (Optional) - The number of results per page to show. Defaults to 100.
     * @param int (Optional) - The unique ID of the 'user' who tagged the object.
     *
     * @return An array of Object ID numbers that reference your original objects.
     */
    function get_objects_with_tag_id($tag_id, $offset = 0, $limit = 100, $tagger_id = NULL)
    {
        if (!isset($tag_id))
            return FALSE;

        $this->ci->db->distinct();
        $this->ci->db->select('object_id');
        $this->ci->db->from(self::TABLE_OBJ);
        $this->ci->db->join(self::TABLE_TAGS, 'tag_id = id', 'inner');
        $this->ci->db->where('id', $tag_id);
        if (isset($tagger_id) && ($tagger_id > 0))
            $this->ci->db->where('tagger_id', $tagger_id);
        $this->ci->db->order_by('object_id', 'desc');
        $this->ci->db->limit($limit, $offset);
        $query = $this->ci->db->get();
        $retarr = array();
        foreach ($query->result_array() as $row)
        {
            $retarr[] = $row['object_id'];
        }
        return $retarr;
    }

    /**
     * get_tags_on_object
     *
     * You can use this function to show the tags on an object. Since it supports both user-specific
     * and general modes with the $tagger_id parameter, you can use it twice on a page to make it work
     * similar to upcoming.org and flickr, where the page displays your own tags differently than
     * other users' tags.
     *
     * @param int The unique ID of the object in question.
     * @param int The offset of tags to return.
     * @param int The size of the tagset to return. Use a zero size to get all tags.
     * @param int The unique ID of the person who tagged the object, if user-level tags only are preferred.
     *
     * @return array Returns a PHP array with object elements ordered by object ID. Each element is an associative
     * array with the following elements:
     *   - 'tag' => Normalized-form tag
     *     - 'raw_tag' => The raw-form tag
     *     - 'tagger_id' => The unique ID of the person who tagged the object with this tag.
     */
    function get_tags_on_object($object_id, $offset = 0, $limit = 10, $tagger_id = NULL)
    {
        if (!isset($object_id))
            return FALSE;

        $this->ci->db->distinct();
        $this->ci->db->select('tag, raw_tag, tagger_id');
        $this->ci->db->from(self::TABLE_OBJ);
        $this->ci->db->join(self::TABLE_TAGS, 'tag_id = id', 'inner');
        $this->ci->db->where('object_id', $object_id);
        if (isset($tagger_id) && ($tagger_id > 0))
            $this->ci->db->where('tagger_id', $tagger_id);
        $this->ci->db->order_by('id');
        if ($limit > 0)
            $this->ci->db->limit($limit, $offset);
        $query = $this->ci->db->get();

        $retarr = array();
        foreach ($query->result_array() as $row)
        {
            $retarr[] = array(
                'tag' => $row['tag'],
                'raw_tag' => $row['raw_tag'],
                'tagger_id' => $row['tagger_id']
            );
        }
        return $retarr;
    }

    /**
     * safe_tag
     *
     * Pass individual tag phrases along with object and person ID's in order to
     * set a tag on an object. If the tag in its raw form does not yet exist,
     * this function will create it.
     * Fails transparently on duplicates, and checks for dupes based on the
     * block_multiuser_tag_on_object constructor param.
     *
     * @param int The unique ID of the person who tagged the object with this tag.
     * @param int The unique ID of the object in question.
     * @param string A raw string from a web form containing tags.
     *
     * @return boolean Returns true if successful, false otherwise. Does not operate as a transaction.
     */
    function safe_tag($tagger_id, $object_id, $tag)
    {
        if (!isset($tagger_id) || !isset($object_id) || !isset($tag))
        {
            show_error("Tags: safe_tag argument missing");
            return FALSE;
        }

        $normalized_tag = $this->normalize_tag($tag);

        $this->ci->db->where('object_id', $object_id);
        $this->ci->db->where('tag', $normalized_tag);
        $this->ci->db->from(self::TABLE_OBJ);
        $this->ci->db->join(self::TABLE_TAGS, 'tag_id = id', 'inner');
        $count = $this->ci->db->count_all_results();
        if ($count > 0)
            return TRUE;
        // Then see if a raw tag in this form exists.
        $this->ci->db->_reset_select();
        $this->ci->db->select('id');
        $this->ci->db->where('raw_tag', $tag);
        $query = $this->ci->db->get(self::TABLE_TAGS);
        if ($query->num_rows() > 0)
        {
            $row = $query->row_array();
            $tag_id = $row['id'];
        }
        else
        {
            // Add new tag!
            $sql_data = array(
                'tag' => $normalized_tag,
                'raw_tag' => $tag,
            );
            $this->ci->db->_reset_select();
            $this->ci->db->insert(self::TABLE_TAGS, $sql_data);
            $tag_id = $this->ci->db->insert_id();
        }
        if (!($tag_id > 0))
            return FALSE;
        $sql_data = array(
            'tag_id' => $tag_id,
            'tagger_id' => $tagger_id,
            'object_id' => $object_id,
                //'tagged_on'=>'NOW()'
        );
        $this->ci->db->_reset_select();
        $this->ci->db->insert(self::TABLE_OBJ, $sql_data);

        return TRUE;
    }

    /**
     * normalize_tag
     *
     * This is a utility function used to take a raw tag and convert it to normalized form.
     * Normalized form is essentially lowercased alphanumeric characters only,
     * with no spaces or special characters.
     *
     * Customize the normalized valid chars with your own set of special characters
     * in regex format within the option 'normalized_valid_chars'. It acts as a filter
     * to let a customized set of characters through.
     *
     * After the filter is applied, the function also lowercases the characters using strtolower
     * in the current locale.
     *
     * The default for normalized_valid_chars is a-zA-Z0-9, or english alphanumeric.
     *
     * @param string An individual tag in raw form that should be normalized.
     *
     * @return string Returns the tag in normalized form.
     */
    function normalize_tag($tag)
    {
        $search = '_';
        $replace = '-';
        $trans = array(
            '&\#\d+?;' => '',
            '&\S+?;' => '',
            '\s+' => $replace,
            '[^a-z0-9\-\._]' => '',
            $replace . '+' => $replace,
            $replace . '$' => $replace,
            '^' . $replace => $replace,
            '\.+$' => ''
        );
        $search = array('ı', 'İ', 'Ğ', 'ğ', 'Ü', 'ü', 'Ş', 'ş', 'Ö', 'ö', 'Ç', 'ç');
        $replace = array('i', 'I', 'G', 'g', 'U', 'u', 'S', 's', 'O', 'o', 'C', 'c');
        $tag = str_replace($search, $replace, $tag);
        $tag = strip_tags($tag);
        foreach ($trans as $key => $val)
            $tag = preg_replace("#" . $key . "#i", $val, $tag);
        $tag = strtolower($tag);
        return trim(stripslashes($tag));
    }

    /**
     * delete_object_tag
     *
     * Removes a tag from an object. This does not delete the tag itself from
     * the database. Since most applications will only allow a user to delete
     * their own tags, it supports raw-form tags as its tag parameter, because
     * that's what is usually shown to a user for their own tags.
     *
     * @param int The unique ID of the person who tagged the object with this tag.
     * @param int The ID of the object in question.
     * @param string The raw string form of the tag to delete. See above for notes.
     *
     * @return string Returns the tag in normalized form.
     */
    function delete_object_tag($tagger_id, $object_id, $tag)
    {
        if (!isset($tagger_id) || !isset($object_id) || !isset($tag))
        {
            show_error("Tags: delete_object_tag argument mising");
            return FALSE;
        }

        $tag_id = $this->get_raw_tag_id($tag);
        if ($tag_id > 0)
        {
            $this->ci->db->where('tagger_id', $tagger_id);
            $this->ci->db->where('object_id', $object_id);
            $this->ci->db->where('tag_id', $tag_id);
            $this->ci->db->limit(1);
            $this->ci->db->delete(self::TABLE_OBJ);
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

    /**
     * delete_all_object_tags
     *
     * Removes all tag from an object. This does not
     * delete the tag itself from the database. This is most useful for
     * cleanup, where an item is deleted and all its tags should be wiped out
     * as well.
     *
     * @param int The ID of the object in question.
     *
     * @return boolean Returns true if successful, false otherwise. It will return true if the tagged object does not exist.
     */
    function delete_all_object_tags($object_id)
    {
        if ($object_id > 0)
        {
            $this->ci->db->where('object_id', $object_id);
            $this->ci->db->delete(self::TABLE_OBJ);
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

    /**
     * delete_all_object_tags_for_user
     *
     * Removes all tag from an object for a particular user. This does not
     * delete the tag itself from the database. This is most useful for
     * implementations similar to del.icio.us, where a user is allowed to retag
     * an object from a text box. That way, it becomes a two step operation of
     * deleting all the tags, then retagging with whatever's left in the input.
     *
     * @param int The unique ID of the person who tagged the object with this tag.
     * @param int The ID of the object in question.
     *
     * @return boolean Returns true if successful, false otherwise. It will return true if the tagged object does not exist.
     */
    function delete_all_object_tags_for_user($tagger_id, $object_id)
    {
        if (!isset($tagger_id) || !isset($object_id))
        {
            show_error("Tags: delete_all_object_tags_for_user argument mising");
            return FALSE;
        }
        if ($object_id > 0)
        {
            $this->ci->db->where('object_id', $object_id);
            $this->ci->db->where('tagger_id', $tagger_id);
            $this->ci->db->delete(self::TABLE_OBJ);
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

    /**
     * get_tag_id
     *
     * Retrieves the unique ID number of a tag based upon its normal form. Actually,
     * using this function is dangerous, because multiple tags can exist with the same
     * normal form, so be careful, because this will only return one, assuming that
     * if you're going by normal form, then the individual tags are interchangeable.
     *
     * @param string The normal form of the tag to fetch.
     *
     * @return string Returns the tag in normalized form.
     */
    function get_tag_id($tag)
    {
        if (!isset($tag))
        {
            show_error("Tags: get_tag_id argument missing");
            return FALSE;
        }
        $this->ci->db->select('id');
        $this->ci->db->where('tag', $tag);
        $this->ci->db->limit(1);
        $query = $this->ci->db->get(self::TABLE_TAGS);
        if ($query->num_rows() > 0)
        {
            $row = $query->row_array();
            return $row['id'];
        }
        return FALSE;
    }

    /**
     * get_raw_tag_id
     *
     * Retrieves the unique ID number of a tag based upon its raw form. If a single
     * unique record is needed, then use this function instead of get_tag_id,
     * because raw_tags are unique.
     *
     * @param string The raw string form of the tag to fetch.
     *
     * @return string Returns the tag in normalized form.
     */
    function get_raw_tag_id($tag)
    {
        if (!isset($tag))
        {
            show_error("Tags: get_tag_id argument mising");
            return FALSE;
        }

        $this->ci->db->select('id');
        $this->ci->db->where('raw_tag', $tag);
        $this->ci->db->limit(1);
        $query = $this->ci->db->get(self::TABLE_TAGS);
        if ($query->num_rows() > 0)
        {
            $row = $query->row_array();
            return $row['id'];
        }
        return FALSE;
    }

    /**
     * tag_object
     *
     * This function allows you to pass in a string directly from a form, which is then
     * parsed for quoted phrases and special characters, normalized and converted into tags.
     * The tag phrases are then individually sent through the safe_tag() method for processing
     * and the object referenced is set with that tag.
     *
     * This method has been refactored to automatically look for existing tags and run
     * adds/updates/deletes as appropriate. It also has been refactored to accept comma-separated lists
     * of tagger_id's and objecct_id's to create either duplicate tagings from multiple taggers or
     * apply the tags to multiple objects. However, a singular tagger_id and object_id still produces
     * the same behavior.
     *
     * @param int A comma-separated list of unique id's of the object(s) in question.
     * @param array The raw string form of the tag to delete. See above for notes.
     * @param int A comma-separated list of unique id's of the tagging subject(s).
     * @param int Whether to skip the update portion for objects that haven't been tagged. (Default: 1)
     *
     * @return string Returns the tag in normalized form.
     */
    function tag_object($object_id_list, $tag_array, $tagger_id_list, $skip_updates = 1)
    {
        if (!is_array($tag_array))
        {
            // If an empty string was passed, just return true, don't die.
            //show_error("Tags: Empty tag string passed");
            return TRUE;
        }
        // Break up CSL's for tagger id's and object id's
        $tagger_id_array = explode(',', $tagger_id_list);
        $valid_tagger_id_array = array();
        foreach ($tagger_id_array as $id)
        {
            if (intval($id) > 0)
            {
                $valid_tagger_id_array[] = intval($id);
            }
        }

        if (count($valid_tagger_id_array) == 0)
        {
            return TRUE;
        }

        $object_id_array = explode(',', $object_id_list);
        $valid_object_id_array = array();
        foreach ($object_id_array as $id)
        {
            if (intval($id) > 0)
            {
                $valid_object_id_array[] = intval($id);
            }
        }

        if (count($valid_object_id_array) == 0)
        {
            return TRUE;
        }

        foreach ($valid_tagger_id_array as $tagger_id)
        {
            foreach ($valid_object_id_array as $object_id)
            {

                $old_tags = $this->get_tags_on_object($object_id, 0, 0, $tagger_id);

                $preserve_tags = array();

                if (($skip_updates == 0) && (count($old_tags) > 0))
                {
                    foreach ($old_tags as $tag_item)
                    {
                        if (!in_array($tag_item['raw_tag'], $tag_array))
                        {
                            // We need to delete old tags that don't appear in the new parsed string.
                            $this->delete_object_tag($tagger_id, $object_id, $tag_item['raw_tag']);
                        }
                        else
                        {
                            // We need to preserve old tags that appear (to save timestamps)
                            $preserve_tags[] = $tag_item['raw_tag'];
                        }
                    }
                }
                $new_tags = array_diff($tag_array, $preserve_tags);

                $this->_tag_object_array($tagger_id, $object_id, $new_tags);
            }
        }

        return TRUE;
    }

    /**
     * _tag_object_array
     *
     * Private method to add tags to an object from an array.
     *
     * @param int Unique ID of tagger
     * @param int Unique ID of object
     * @param array Array of tags to add.
     *
     * @return boolean True if successful, false otherwise.
     */
    function _tag_object_array($tagger_id, $object_id, $tag_array)
    {
        foreach ($tag_array as $tag)
        {
            $tag = trim($tag);
            if (($tag != '') && (strlen($tag) <= $this->_MAX_TAG_LENGTH))
            {
                $this->safe_tag($tagger_id, $object_id, $tag);
            }
        }
        return TRUE;
    }

    /**
     * get_most_popular_tags
     *
     * This function returns the most popular tags in the Tags system, with
     * offset and limit support for pagination. It also supports restricting to
     * an individual user. Call it with no parameters for a list of 25 most popular
     * tags.
     *
     * @param int The unique ID of the person to restrict results to.
     * @param int The offset of the tag to start at.
     * @param int The number of tags to return in the result set.
     *
     * @return array Returns a PHP array with tags ordered by popularity descending.
     * Each element is an associative array with the following elements:
     *   - 'tag' => Normalized-form tag
     *     - 'count' => The number of objects tagged with this tag.
     */
    function get_most_popular_tags($tagger_id = NULL, $offset = 0, $limit = 25)
    {

        $this->ci->db->select('tag, COUNT(*) as count');
        $this->ci->db->from(self::TABLE_TAGS);
        $this->ci->db->join(self::TABLE_OBJ, 'id = tag_id', 'inner');
        if (isset($tagger_id) && ($tagger_id > 0))
            $this->ci->db->where('tagger_id', $tagger_id);
        $this->ci->db->group_by('tag');
        $this->ci->db->order_by('count', 'desc');
        $this->ci->db->order_by('tag', 'asc');
        $this->ci->db->limit($limit, $offset);
        $query = $this->ci->db->get();

        $retarr = array();
        foreach ($query->result_array() as $row)
        {
            $retarr[] = array(
                'raw_tag' => $row['raw_tag'],
                'tag' => $row['tag'],
                'count' => $row['count']
            );
        }
        return $retarr;
    }

    /**
     * get_most_recent_objects
     *
     * This function returns the most recent object ids in the
     * Tags system, with offset and limit support for
     * pagination. It also supports restricting to an individual
     * user. Call it with no parameters for a list of 25 most
     * recent tags.
     *
     * @param int The unique ID of the person to restrict results to.
     * @param string Tag to filter by
     * @param int The offset of the object to start at.
     * @param int The number of object ids to return in the result set.
     *
     * @return array Returns a PHP array with object ids ordered by
     * timestamp descending.
     * Each element is an associative array with the following elements:
     * - 'object_id' => Object id
     * - 'tagged_on' => The timestamp of each object id
     */
    function get_most_recent_objects($tagger_id = NULL, $tag = NULL, $offset = 0, $limit = 25)
    {

        if (!$tag)
        {
            $this->ci->db->distinct();
            $this->ci->db->select('object_id, tagged_on');
            $this->ci->db->from(self::TABLE_OBJ);
            if (isset($tagger_id) && ($tagger_id > 0))
                $this->ci->db->where('tagger_id', $tagger_id);
            $this->ci->db->order_by('tagged_on', 'desc');
            $this->ci->db->limit($limit, $offset);
        }
        else
        {
            $this->ci->db->distinct();
            $this->ci->db->select('object_id, tagged_on');
            $this->ci->db->from(self::TABLE_OBJ);
            $this->ci->db->join(self::TABLE_TAGS, 'tag_id = id', 'inner');
            $this->ci->db->where('tag', $tag);
            if (isset($tagger_id) && ($tagger_id > 0))
                $this->ci->db->where('tagger_id', $tagger_id);
            $this->ci->db->order_by('tagged_on', 'desc');
            $this->ci->db->limit($limit, $offset);
        }
        $query = $this->ci->db->get();
        $retarr = array();
        foreach ($query->result_array() as $row)
        {
            $retarr[] = array(
                'object_id' => $row['object_id'],
                'tagged_on' => $row['tagged_on']
            );
        }
        return $retarr;
    }

    /**
     * count_tags
     *
     * Returns the total number of tag->object links in the system.
     * It might be useful for pagination at times, but i'm not sure if I actually use
     * this anywhere. Restrict to a person's tagging by using the $tagger_id parameter.
     * It does NOT include any tags in the system that aren't directly linked
     * to an object.
     *
     * @param int The unique ID of the person to restrict results to.
     *
     * @return int Returns the count
     */
    function count_tags($tagger_id = NULL, $normalized_version = 0)
    {
        $distinct_col = ($normalized_version == 1) ? 'tag' : 'tag_id';
        $this->ci->db->select('COUNT(DISTINCT ' . $distinct_col . ') as count');
        $this->ci->db->from(self::TABLE_TAGS);
        $this->ci->db->join(self::TABLE_OBJ, 'id = tag_id', 'inner');
        if (isset($tagger_id) && ($tagger_id > 0))
            $this->ci->db->where('tagger_id', $tagger_id);
        $query = $this->ci->db->get();
        if ($query->num_rows() > 0)
        {
            $row = $query->row_array();
            return $row['count'];
        }
        return FALSE;
    }

    /**
     * get_tag_cloud_html
     *
     * This is a pretty straightforward, flexible method that automatically
     * generates some html that can be dropped in as a tag cloud.
     * It uses explicit font sizes inside of the style attribute of SPAN
     * elements to accomplish the differently sized objects.
     *
     * It will also link every tag to $tag_page_url, appended with the
     * normalized form of the tag. You should adapt this value to your own
     * tag detail page's URL.
     *
     * @param int The number of tags to return. (default: 100)
     * @param int The minimum font size in the cloud. (default: 10)
     * @param int The maximum font size in the cloud. (default: 20)
     * @param string The "units" for the font size (i.e. 'px', 'pt', 'em') (default: px)
     * @param string The class to use for all spans in the cloud. (default: cloud_tag)
     * @param string The tag page URL (default: /tag/)
     * @param int Specify starting record (default: 0)
     *
     * @return string Returns an HTML snippet that can be used directly as a tag cloud.
     */
    function get_tag_cloud_html($num_tags = 100, $min_font_size = 10, $max_font_size = 20, $font_units = 'px', $span_class = 'cloud_tag', $tag_page_url = '/tag/', $tagger_id = NULL, $offset = 0)
    {
        $tag_list = $this->get_tag_cloud_tags($num_tags, $tagger_id, $offset);
        // Get the maximum qty of tagged objects in the set
        if (count($tag_list))
        {
            $max_qty = max(array_values($tag_list));
            // Get the min qty of tagged objects in the set
            $min_qty = min(array_values($tag_list));
        }
        else
        {
            return '';
        }

        // For ever additional tagged object from min to max, we add
        // $step to the font size.
        $spread = $max_qty - $min_qty;
        if (0 == $spread)
        { // Divide by zero
            $spread = 1;
        }
        $step = ($max_font_size - $min_font_size) / ($spread);

        // Since the original tag_list is alphabetically ordered,
        // we can now create the tag cloud by just putting a span
        // on each element, multiplying the diff between min and qty
        // by $step.
        $cloud_html = '';
        $cloud_spans = array();
        foreach ($tag_list as $tag => $qty)
        {
            $size = $min_font_size + ($qty - $min_qty) * $step;
            $cloud_span[] = '<span class="' . $span_class . '" style="font-size: ' . $size . $font_units . '"><a href="' . $tag_page_url . $tag . '">' . htmlspecialchars(stripslashes($tag)) . '</a></span>';
        }
        $cloud_html = join("\n ", $cloud_span);

        return $cloud_html;
    }

    /*
     * get_tag_cloud_tags
     *
     * This is a function built explicitly to set up a page with most popular tags
     * that contains an alphabetically sorted list of tags, which can then be sized
     * or colored by popularity.
     *
     * Also known more popularly as Tag Clouds!
     *
     * Here's the example case: http://upcoming.org/tag/
     *
     * @param int The maximum number of tags to return.
     * @param int The unique ID of the tagger to restrict to (Optional, NULL default)
     * @param int Specify starting record (default: 0)
     *
     * @return array Returns an array where the keys are normalized tags, and the
     * values are numeric quantity of objects tagged with that tag.
     */

    function get_tag_cloud_tags($max = 100, $tagger_id = NULL, $offset = 0)
    {
        $max = intval($max);
        $offset = intval($offset);
        $this->ci->db->select('raw_tag, COUNT(object_id) AS quantity');
        $this->ci->db->from(self::TABLE_TAGS);
        $this->ci->db->join(self::TABLE_OBJ, 'id = tag_id', 'inner');
        if (isset($tagger_id) && ($tagger_id > 0))
            $this->ci->db->where('tagger_id', $tagger_id);
        $this->ci->db->group_by('raw_tag');
        $this->ci->db->order_by('quantity', 'desc');
        if ($offset >= 0 && $max >= 0)
            $this->ci->db->limit($max, $offset);
        elseif ($max >= 0)
            $this->ci->db->limit($max, 0);
        else
            $this->ci->db->limit(100, 0);
        $query = $this->ci->db->get();
        $retarr = array();
        foreach ($query->result_array() as $row)
        {
            $retarr[$row['raw_tag']] = $row['quantity'];
        }
        ksort($retarr);
        return $retarr;
    }

    /**
     * count_unique_tags
     * An alias to count_tags.
     *
     * @param int The unique ID of the person to restrict results to.
     * @param int Whether to count normalized tags or all raw tags (0 for raw, 1 for normalized, 0 default)
     *
     * @return int Returns the count
     */
    function count_unique_tags($tagger_id = NULL, $normalized_version = 0)
    {
        return $this->count_tags($tagger_id, $normalized_version);
    }

    /**
     * similar_tags
     *
     * Finds tags that are "similar" or related to the given tag.
     * It does this by looking at the other tags on objects tagged with the tag specified.
     * Confusing? Think of it like e-commerce's "Other users who bought this also bought,"
     * as that's exactly how this works.
     *
     * Returns an empty array if no tag is passed, or if no related tags are found.
     * Hint: You can detect related tags returned with count($retarr > 0)
     *
     * It's important to note that the quantity passed back along with each tag
     * is a measure of the *strength of the relation* between the original tag
     * and the related tag. It measures the number of objects tagged with both
     * the original tag and its related tag.
     *
     * Thanks to Myles Grant for contributing this function!
     *
     * @param string The raw normalized form of the tag to fetch.
     * @param int The maximum number of tags to return.
     * @param int The unique id of a user to restrict the search to. Optional.
     *
     * @return array Returns an array where the keys are normalized tags, and the
     * values are numeric quantity of objects tagged with BOTH tags, sorted by
     * number of occurences of that tag (high to low).
     */
    function similar_tags($tag, $max = 100, $tagger_id = NULL)
    {
        $retarr = array();
        if (!isset($tag))
            return $retarr;

        // This query was written using a double join for PHP. If you're trying to eke
        // additional performance and are running MySQL 4.X, you might want to try a subselect
        // and compare perf numbers.
        $this->ci->db->select('t1.tag, COUNT( o1.object_id ) AS quantity');
        $this->ci->db->from(self::TABLE_OBJ . ' o1');
        $this->ci->db->join(self::TABLE_TAGS . ' t1', 't1.id = o1.tag_id', 'inner');
        $this->ci->db->join(self::TABLE_OBJ . ' o2', 'o1.object_id = o2.object_id', 'inner');
        $this->ci->db->join(self::TABLE_TAGS . ' t2', 't2.id = o2.tag_id', 'inner');
        $this->ci->db->where('t2.tag', $tag);
        $this->ci->db->where('t1.tag !=', $tag);
        if (isset($tagger_id) && intval($tagger_id) > 0)
        {
            $this->ci->db->where('o1.tagger_id', intval($tagger_id));
            $this->ci->db->where('o2.tagger_id', intval($tagger_id));
        }
        $this->ci->db->group_by('o1.tag_id');
        $this->ci->db->order_by('quantity', 'desc');
        $this->ci->db->limit($max);
        $query = $this->ci->db->get();
        foreach ($query->result_array() as $row)
        {
            $retarr[$row['tag']] = $row['quantity'];
        }

        return $retarr;
    }

    /**
     * similar_objects
     *
     * This method implements a simple ability to find some objects in the database
     * that might be similar to an existing object. It determines this by trying
     * to match other objects that share the same tags.
     *
     * The user of the method has to use a threshold (by default, 1) which specifies
     * how many tags other objects must have in common to match. If the original object
     * has no tags, then it won't match anything. Matched objects are returned in order
     * of most similar to least similar.
     *
     * The more tags set on a database, the better this method works. Since this
     * is such an expensive operation, it requires a limit to be set via max_objects.
     *
     * @param int The unique ID of the object to find similar objects for.
     * @param int The Threshold of tags that must be found in common (default: 1)
     * @param int The maximum number of similar objects to return (default: 5).
     * @param int Optionally pass a tagger id to restrict similarity to a tagger's view.
     *
     * @return array Returns a PHP array with matched objects ordered by strength of match descending.
     * Each element is an associative array with the following elements:
     * - 'strength' => A floating-point strength of match from 0-1.0
     * - 'object_id' => Unique ID of the matched object
     *
     */
    function similar_objects($object_id, $threshold = 1, $max_objects = 5, $tagger_id = NULL)
    {

        $retarr = array();

        $object_id = intval($object_id);
        $threshold = intval($threshold);
        $max_objects = intval($max_objects);
        if (!isset($object_id) || !($object_id > 0))
        {
            return $retarr;
        }
        if ($threshold <= 0)
        {
            return $retarr;
        }
        if ($max_objects <= 0)
        {
            return $retarr;
        }

        // Pass in a zero-limit to get all tags.
        $tag_items = $this->get_tags_on_object($object_id, 0, 0);

        $tag_array = array();
        foreach ($tag_items as $tag_item)
        {
            $tag_array[] = $tag_item['tag'];
        }
        $tag_array = array_unique($tag_array);

        $num_tags = count($tag_array);
        if ($num_tags == 0)
        {
            return $retarr; // Return empty set of matches
        }

        $this->ci->db->select('matches.object_id, COUNT( matches.object_id ) AS num_common_tags');
        $this->ci->db->from(self::TABLE_OBJ . ' as matches');
        $this->ci->db->join(self::TABLE_TAGS . ' as tags', 'tags.id = matches.tag_id', 'inner');
        $this->ci->db->where_in('tags.tag', $tag_array);
        $this->ci->db->group_by('matches.object_id');
        $this->ci->db->having('num_common_tags >=', $threshold);
        $this->ci->db->order_by('num_common_tags', 'desc');
        $this->ci->db->limit($max_objects);
        $query = $this->ci->db->get();
        foreach ($query->result_array() as $row)
        {
            $retarr[] = array(
                'object_id' => $row['object_id'],
                'strength' => ($row['num_common_tags'] / $num_tags)
            );
        }
        return $retarr;
    }

    function get_tag_by_slug($tag)
    {
        if (!isset($tag))
        {
            show_error("Tags: get_tag argument missing");
            return FALSE;
        }
        $this->ci->db->select('id,tag,raw_tag');
        $this->ci->db->where('tag', $tag);
        $this->ci->db->limit(1);
        $query = $this->ci->db->get(self::TABLE_TAGS);
        if ($query->num_rows() > 0)
        {
            return $query->row_array();
        }
        return FALSE;
    }

}

/* End of file Tags.php */