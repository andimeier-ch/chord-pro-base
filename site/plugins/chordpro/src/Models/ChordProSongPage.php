<?php

namespace AndiMeier\ChordPro\Models;

use Kirby\Cms\Page;
use ChordPro\Parser;
use ChordPro\Formatter\JSONFormatter;
use ChordPro\Formatter\HtmlFormatter;

class ChordProSongPage extends Page
{
    private $parsedSong = null;

    private function ensureParsedSong() {
        if (!$this->parsedSong) {
            $parser = new Parser();
            $this->parsedSong = $parser->parse($this->chordProRaw());
        }
    }

    /**
     * Raw ChordPro text from the `chordProCode` field
     *
     * @return string
     */
    public function chordProRaw(): string
    {
        return $this->chordProCode()->value() ?? '';
    }
    
    /**
     * Parsed ChordPro as JSON string
     *
     * @return string
     */
    public function chordProJSON($textOnly = false): string
    {
        $this->ensureParsedSong();
        $jsonFormatter = new JSONFormatter();

        $options = [
            'french' => false,
            'no_chords' => $textOnly,
        ];
    
        return $jsonFormatter->format($this->parsedSong, $options);
    }

    /**
     * Parsed ChordPro as HTML string
     * 
     * @return string
     */
    public function chordProHTML(): string
    {
        $this->ensureParsedSong();
        $htmlFormatter = new HtmlFormatter();

        $options = [
            'french' => false,
            'no_chords' => false,
        ];

        return $htmlFormatter->format($this->parsedSong, $options);
    }
}
