<?php

/**
 * Contains profile information on subject
 *
 * @author Matthew McNaney <mcnaney at gmail dot com>
 * @version $Id: Profile.php 7776 2010-06-11 13:52:58Z jtickle $
 */

PHPWS_Core::configRequireOnce('profiler', 'config.php');

// Error defines
define('PFL_PROFILE_NOT_FOUND', 1);

class Profile {
    public $id              = 0;
    public $firstname       = null;
    public $lastname        = null;
    public $photo_large     = 0;     // Id to the photo
    public $photo_medium    = 0;     // Id to the photo
    public $photo_small     = 0;     // Id to the photo
    public $fullstory       = null;  // Complete prose to profile
    public $caption         = null;  // Abbreviated intro to the profile
    public $email           = null;  // Email to contact profile
    public $website         = null;  // Web site run by profile
    public $profile_type    = 0;     // Profile type number, see defines above
    public $keywords        = null;  // Searchable words to find a profile
    public $submit_date     = 0;     // Date of profile creation
    public $contributor     = null;  // Name of contributor
    public $contributor_id  = 0;
    public $approved        = 0;
    public $_error          = null;  // Error object holder
    public $_db             = null;  // Database object
    public $_division_title = null;

    public function Profile($id=null)
    {
        if (empty($id)) {
            return TRUE;
        }

        $this->setId($id);
        $result = $this->init();
        if (PHPWS_Error::isError($result)) {
            $this->_error = $result;
            return FALSE;
        }
        return TRUE;
    }

    public function resetdb()
    {
        if (isset($this->_db)) {
            $this->_db->reset();
        } else {
            $this->_db = new PHPWS_DB('profiles');
        }
    }

    public function display($template_name)
    {
        Layout::addStyle('profiler');

        $images = $this->loadImages();

        $template_name = preg_replace('/\W/u', '', $template_name);

        $template['FIRST_NAME'] = $this->firstname;
        $template['LAST_NAME'] = $this->lastname;

        $link = PHPWS_Core::getHomeHttp() . 'index.php?module=profiler&amp;user_cmd=view_profile&amp;id=' . $this->id;

        if (!empty($images['small'])) {
            $image = & $images['small'];
            $image->allowImageLink(false);
            $template['PHOTO_SMALL'] = sprintf('<a href="%s">%s</a>', $link, $image->getTag());
        }

        if (!empty($images['medium'])) {
            $template['PHOTO_MEDIUM'] = $images['medium']->getTag();
        }

        if (!empty($images['large'])) {
            $template['PHOTO_LARGE'] = $images['large']->getTag();
        }

        $template['FULLSTORY'] = $this->getFullstory();
        $template['CAPTION'] = $this->getCaption();
        $template['READ_MORE'] = sprintf('<a href="%s">%s</a>', $link, dgettext('profiler', 'Read more. . .'));
        $template['WEBSITE_LABEL'] = dgettext('profiler', 'Web site');
        $template['WEBSITE'] = $this->getWebsite();

        $template['EMAIL'] = $this->getEmail();
        $template['EMAIL_LABEL'] = dgettext('profiler', 'Email address');

        return PHPWS_Template::process($template, 'profiler', 'views/' . $template_name . '.tpl');
    }

    public function getEmail()
    {
        if (empty($this->email)) {
            return null;
        }

        return sprintf('<a class="email" href="mailto:%s">%s</a>', $this->email, Icon::show('email'));
    }

    public function getWebsite()
    {
        if (empty($this->website)) {
            return null;
        }

        return sprintf('<a class="url" href="%s"><img src="%smod/profiler/img/website.png" alt="%s" title="%s" /></a>', $this->website, PHPWS_SOURCE_HTTP, dgettext('profiler', 'Web site'), dgettext('profiler', 'Web site'));
    }

    public function loadImages()
    {
        PHPWS_Core::initModClass('filecabinet', 'Cabinet.php');
        $images['small'] = $images['medium'] = $images['large'] = null;

        if ($this->photo_small) {
            $images['small'] = Cabinet::getFile($this->photo_small);
        }

        if ($this->photo_medium) {
            $images['medium'] = Cabinet::getFile($this->photo_medium);
        }

        if ($this->photo_large) {
            $images['large'] = Cabinet::getFile($this->photo_large);
        }

        return $images;
    }

    public function setId($id)
    {
        $this->id = (int)$id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setFirstName($firstname)
    {
        $this->firstname = preg_replace('/[^\w\s]/u', '', trim($firstname));
    }

    public function setLastName($lastname)
    {
        $this->lastname = preg_replace('/^\w\s/u', '', trim($lastname));
    }

    public function setCaption($caption)
    {
        $this->caption = PHPWS_Text::parseInput($caption);
    }

    public function getCaption($formatted=TRUE)
    {
        if ($formatted) {
            return PHPWS_Text::parseTag(PHPWS_Text::parseOutput($this->caption));
        } else {
            return $this->caption;
        }
    }

    public function setFullstory($fullstory)
    {
        $this->fullstory = PHPWS_Text::parseInput($fullstory);
    }

    public function setProfileType($profile_type)
    {
        $this->profile_type = (int)$profile_type;
    }

    public function setSubmitDate()
    {
        $this->submit_date = time();
    }

    public function getFullstory($formatted=TRUE)
    {
        if ($formatted) {
            return PHPWS_Text::parseTag(PHPWS_Text::parseOutput($this->fullstory));
        } else {
            return $this->fullstory;
        }
    }

    public function getProfileType()
    {
        static $all_profiles = array();

        if (empty($all_profiles)) {
            $div = new PHPWS_DB('profiler_division');
            $div->addWhere('show_homepage', 1);
            $div->addOrder('title');
            $div->addColumn('id');
            $div->addColumn('title');
            $div->setIndexBy('id');
            $all_profiles = $div->select('col');
        }

        if (isset($all_profiles[$this->profile_type])) {
            return $all_profiles[$this->profile_type];
        } else {
            return dgettext('profiler', 'Profile not set.');
        }
    }

    public function init()
    {
        $this->resetdb();
        $result = $this->_db->loadObject($this);

        if (PHPWS_Error::isError($result)) {
            return $result;
        } elseif (empty($result)) {
            return PHPWS_Error::get(PFL_PROFILE_NOT_FOUND, 'profiler',
                                    'Profile::init', 'Id:' . $this->id);
        }
        return TRUE;
    }

    public function postProfile()
    {
        PHPWS_Core::initModClass('filecabinet', 'Image.php');

        if (!Current_User::authorized('profiler')) {
            Current_User::disallow();
            return FALSE;
        }

        if (empty($_POST['firstname'])) {
            $error[] = dgettext('profiler', 'Please enter a first name.');
        }

        $this->setFirstName($_POST['firstname']);

        if (empty($_POST['lastname'])) {
            $error[] = dgettext('profiler', 'Please enter a last name.');
        }

        $this->setLastName($_POST['lastname']);
        $this->setCaption($_POST['caption']);
        $this->setFullStory($_POST['fullstory']);
        $this->setProfileType($_POST['profile_type']);

        $this->photo_large  = (int)$_POST['photo_large'];
        $this->photo_medium = (int)$_POST['photo_medium'];
        $this->photo_small  = (int)$_POST['photo_small'];

        if (empty($this->submitted_date)) {
            $this->setSubmitDate();
        }

        if (empty($this->contributor_id)) {
            $this->contributor_id = Current_User::getId();
            $this->contributor = Current_User::getUsername();
        }

        if (!empty($_POST['website'])) {
            $link = PHPWS_Text::checkLink($_POST['website']);
            $this->website = $link;
            if (!PHPWS_Text::isValidInput($link, 'url')) {
                $error[] = dgettext('profiler', 'Web site address does not appear valid.');
            }
        } else {
            $this->website = null;
        }

        if (isset($_POST['email'])) {
            $this->email = $_POST['email'];
            if (!PHPWS_Text::isValidInput($this->email, 'email')) {
                $error[] = dgettext('profiler', 'Email address does not appear valid.');
            }
        } else {
            $this->email = null;
        }

        if (isset($_POST['version_id'])) {
            $this->approved = 0;
        } elseif (Current_User::isRestricted('profiler')) {
            $this->approved = 0;
        } else {
            $this->approved = 1;
        }

        if (isset($error)) {
            return $error;
        } else {
            return TRUE;
        }
    }

    public function getProfileTags()
    {
        //        $tpl['PROFILE_TYPE'] = $this->getProfileType();
        $tpl['PROFILE_TYPE'] = $this->_division_title;

        $vars['profile_id'] = $this->id;
        $vars['command'] = 'edit';
        $links[] = PHPWS_Text::secureLink(dgettext('profiler', 'Edit'), 'profiler', $vars);

        $tpl['SUBMIT_DATE'] = strftime(PRF_SUBMIT_DATE_FORMAT, $this->submit_date);

        if (Current_User::allow('profiler', 'delete_profiles')) {
            $vars['command'] = 'delete';
            $confirm_vars['QUESTION'] = dgettext('profiler', 'Are you sure you want to permanently delete this profile?');
            $confirm_vars['ADDRESS'] = PHPWS_Text::linkAddress('profiler', $vars, TRUE);
            $confirm_vars['LINK'] = dgettext('profiler', 'Delete');
            $links[] = Layout::getJavascript('confirm', $confirm_vars);
        }

        $tpl['ACTION'] = implode(' | ', $links);
        return $tpl;
    }

    public function delete()
    {
        PHPWS_Core::initModClass('version', 'Version.php');
        $this->resetdb();
        $this->_db->addWhere('id', $this->id);
        $result = $this->_db->delete();
        if (PHPWS_Error::isError($result)) {
            return $result;
        }

        return Version::flush('profiles', $this->id);
    }

    public function save()
    {
        PHPWS_Core::initModClass('version', 'Version.php');

        if ($this->approved || !$this->id) {
            $this->resetdb();
            $result = $this->_db->saveObject($this);
            if (PHPWS_Error::isError($result)) {
                return $result;
            }
        }

        $version = new Version('profiles');
        $version->setSource($this);
        $version->setApproved($this->approved);
        return $version->save();
    }

}


?>