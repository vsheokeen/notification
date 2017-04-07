<?php
// This file is part of notifies block.
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * notifies block
 *
 * @package block_notifies
 * @author  Vikas Sheokand <vikas@virasatsolutions.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @copyright  (C) 2016 onwards Vikas Sheokand  http://virasatsolutions.com/
 *
 */

defined('MOODLE_INTERNAL') || die();
require_login();
/**
 * The block_notifies custom block class
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @copyright (C) 2016 onwards Vikas Sheokand  http://virasatsolutions.com/
 */
class block_notifies extends block_base {

    /**
     * Set the plugin title name and furl variable
     *
     * @return this variable
     */
    public function init() {
        global $CFG;
        $this->title = get_string('pluginname', 'block_notifies');
        $this->furl = $CFG->wwwroot;
    }

    /**
     * Set the instance
     *
     * @return true
     */
    public function instance_allow_multiple() {
        return true;
    }

    /**
     * Set the block config
     *
     * @return false
     */
    public function has_config() {
        return false;
    }

    /**
     * Set the formats
     *
     * @return format array
     */
    public function applicable_formats() {
        return array('all' => true, 'my' => false, 'tag' => false); // TO DISPLAY UNDER COURSE ONLY.
    }

    /**
     * Set the instance
     *
     * @return true
     */
    public function instance_allow_config() {
        return true;
    }

    /**
     * Set the $this variable value
     *
     * @return true
     */
    public function specialization() {
        global $CFG;
        $this->title = !empty($this->config->title) ? $this->config->title : get_string('pluginname', 'block_notifies');
        $this->furl = !empty($this->config->furl) ? $this->config->furl : $CFG->wwwroot;
    }

    /**
     * get content form moodle block function
     *
     * @return $this->content variable
     */
    public function get_content() {
        if ($this->content !== null) {
            return $this->content;
        }

        global $CFG;
        global $SESSION;
        global $COURSE, $USER;
        global $DB, $OUTPUT, $PAGE;

        $PAGE->requires->css('/blocks/notifies/styles.css', true);
        require_once( $CFG->libdir.'/blocklib.php' );

        $this->content         = new stdClass;
        $this->content->items  = array();
        $this->content->icons  = array();

        $events = $DB->count_records('event', array ('courseid' => $COURSE->id,  'visible' => 1));
        $event = '';
        if ($events > 0) {
            $event = $events;
            $eventcss = 'notifies';
        } else {
            $eventcss = 'notifies_hidden';
        }

        // NEWS COUNTER.
        $newsidnew = ($COURSE->id) - 1;
        $newsid = $DB->get_field('forum', 'id', array('course' => $COURSE->id, 'type' => 'news' ));

        $sqltn = "SELECT fp.id
                    FROM {forum_posts} fp
              INNER JOIN {forum_discussions} fd ON fd.id = fp.discussion
              INNER JOIN {forum} f ON f.id = fd.forum
                   WHERE f.type = :news and f.course = :courseid";
        $totalpostpn = $DB->get_recordset_sql($sqltn, array('news' => 'news', 'courseid' => $COURSE->id));
        $totalpostn = 0;
        foreach ($totalpostpn as $tot) {
            $roughc = $tot->id;
            $totalpostn++;
        }

        $sqlrn = "SELECT fr.postid
                    FROM {forum_read} fr
              INNER JOIN {forum} f ON f.id = fr.forumid
                   WHERE f.type = :news AND f.course = :courseid AND fr.userid = :userid";
        $readn = $DB->get_recordset_sql($sqlrn, array('news' => 'news', 'courseid' => $COURSE->id, 'userid' => $USER->id));
        $readpostn = 0;
        foreach ($readn as $readb) {
            $roughd = $readb->postid;
            $readpostn++;
        }
        $newscount = $totalpostn - $readpostn;
        $news = '';
        if ($newscount > 0) {
            $news = $newscount;
            $newscss = 'notifies';
        } else {
            $newscss = 'notifies_hidden';
        }
        // NEWS COUNTER END.
        // FORUM COUNTER.

        $sqltf = "SELECT fp.id
                    FROM {forum_posts} fp
			  INNER JOIN {forum_discussions} fd ON fd.id = fp.discussion
			  INNER JOIN {forum} f ON f.id = fd.forum
				   WHERE f.type = :general AND f.course = :courseid";

        $totalpostpf = $DB->get_recordset_sql($sqltf, array('general' => 'general', 'courseid' => $COURSE->id));
        $totalpostforum = 0;
        foreach ($totalpostpf as $tot) {
            $rougha = $tot->id;
            $totalpostforum++;
        }

        $sqlrf = "SELECT fr.postid
                    FROM {forum_read} fr
              INNER JOIN {forum} f ON f.id = fr.forumid
                   WHERE f.type = :general AND f.course = :courseid AND fr.userid = :userid";
        $readf = $DB->get_recordset_sql($sqlrf, array('general' => 'general', 'courseid' => $COURSE->id, 'userid' => $USER->id));
        $readpostforum = 0;
        foreach ($readf as $reada) {
            $roughb = $reada->postid;
            $readpostforum++;
        }

        $forumct = $totalpostforum - $readpostforum;
        $forumcount = '';
        if ($forumct > 0) {
            $forumcount = $forumct;
            $forumcss = 'notifies';
        } else {
            $forumcss = 'notifies_hidden';
        }
        // FORUM COUNTER END.

        $msgs = $DB->count_records('message', array ('useridto' => $USER->id));
        $msg = '';
        if ( $msgs > 0) {
            $msg = $msgs;
            $msgcss = 'notifies';
        } else {
            $msgcss = 'notifies_hidden';
        }

        $eventurl = new moodle_url('/calendar/view.php', array('view' => 'upcoming', 'course' => $COURSE->id));
        $newsurl = new moodle_url('/mod/forum/view.php', array('id' => $newsid));
        $messagesurl = new moodle_url('/message/index.php');
        $htmloutput = '';
        $htmloutput .= html_writer::start_tag('div', array('class' => 'height'));
        $htmloutput .= '<a href="'.$eventurl.'"><img src="'.$CFG->wwwroot.'/blocks/notifies/images/event1.png" alt="event pic"
                       /><span class="'.$eventcss.'">'.$event.' </span> &nbsp; <p class="abc">'.get_string('events',
                       'block_notifies').'</p></a>';
        $htmloutput .= html_writer::end_tag('div');

        $htmloutput .= html_writer::start_tag('div', array('class' => 'height'));
        $htmloutput .= '<a href="'.$newsurl.'"><img src="'.$CFG->wwwroot.'/blocks/notifies/images/news1.png" alt="news pic" />
                       <span class="'.$newscss.'">'.$news.' </span> &nbsp; <p class="abc">'.get_string('news', 'block_notifies')
                       .'</p></a>';
        $htmloutput .= html_writer::end_tag('div');

        $htmloutput .= html_writer::start_tag('div', array('class' => 'height'));
        $htmloutput .= '<a href="'.$messagesurl.'"><img src="'.$CFG->wwwroot.'/blocks/notifies/images/msg1.png" alt="msg pic" />
                       <span class="'.$msgcss.'">'.$msg.' </span> &nbsp; <p class="abc">'.get_string('messages',
                        'block_notifies').'</p></a>';
        $htmloutput .= html_writer::end_tag('div');

        $htmloutput .= html_writer::start_tag('div', array('class' => 'height'));
        $htmloutput .= '<a href="'.$this->furl.'"><img src="'.$CFG->wwwroot.'/blocks/notifies/images/forum1.png"
                       alt="forum pic" /><span class="'.$forumcss.'">'.$forumcount.' </span> &nbsp; <p class="abc">'.
                       get_string('forums', 'block_notifies').'</p></a>';
        $htmloutput .= html_writer::end_tag('div');

        $this->content->footer = $htmloutput;
    }

    /**
     * Returns the role that best describes the navigation block... 'navigation'
     *
     * @return string 'navigation'
     */
    public function get_aria_role() {
        return 'navigation';
    }
}
