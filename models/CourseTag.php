<?php
class CoursTag {
    private $coursId;
    private $tagId;

    public function __construct($coursId, $tagId) {
        $this->coursId = $coursId;
        $this->tagId = $tagId;
    }

    // Getters
    public function getCoursId(): int { return $this->coursId; }
    public function getTagId(): int { return $this->tagId; }
}
