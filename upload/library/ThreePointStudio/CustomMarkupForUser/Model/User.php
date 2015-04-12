<?php
/*
* Custom Username Markup For User v1.0.0 written by tyteen4a03@3.studIo.
* This software is licensed under the BSD 2-Clause modified License.
* See the LICENSE file within the package for details.
*/

class ThreePointStudio_CustomMarkupForUser_Model_User extends XFCP_ThreePointStudio_CustomMarkupForUser_Model_User {
    public function rebuildCustomMarkupCache($userId, $category=null) {
        $user = $this->getUserById($userId);
        $options = unserialize($user["3ps_cmfu_options"]);
        /* @var $dr XenForo_Model_DataRegistry */
        $dr = self::create("XenForo_Model_DataRegistry");
        $renderCache = $dr->get("3ps_cmfu_render_cache_" . $userId);
        if ($category) {
            if (!in_array($category, ThreePointStudio_CustomMarkupForUser_Constants::$categories)) {
                throw new UnexpectedValueException("Unknown category");
            }
            $renderCache[$category] = ThreePointStudio_CustomMarkupForUser_Helpers::assembleCustomMarkup($options, $category);
        } else {
            foreach (ThreePointStudio_CustomMarkupForUser_Constants::$categories as $category) {
                $renderCache[$category] = ThreePointStudio_CustomMarkupForUser_Helpers::assembleCustomMarkup($options, $category);
            }
        }
        $dr->set("3ps_cmfu_render_cache_" . $userId, $renderCache);
    }

    public function insertDefaultCustomMarkup($userId) {
        $db = $this->_getDb();
        $db->update("xf_user",
            array("3ps_cmfu_options" => serialize(ThreePointStudio_CustomMarkupForUser_Constants::$defaultOptionsArray)),
            'user_id = ' . $db->quote($userId)
        );
    }
}