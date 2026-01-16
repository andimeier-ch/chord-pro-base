<?php

namespace AndiMeier\ChordPro;

class Parser
{
    /**
     * Parse ChordPro text into a structured array.
     * Uses a bundled fallback parser if the third-party library is not available.
     *
     * @param string $text
     * @return array
     */
    public static function parseToArray(string $text): array
    {
        // If the third-party library is installed, try to use it.
        // This code is defensive: it only calls methods if they exist so it won't
        // produce fatal errors if the library API differs slightly.
        if (class_exists('\\NicolasWurtz\\ChordPro\\Parser') || class_exists('\\ChordPro\\Parser')) {
            try {
                if (class_exists('\\NicolasWurtz\\ChordPro\\Parser')) {
                    $parser = new \NicolasWurtz\ChordPro\Parser();
                } else {
                    $parser = new \ChordPro\Parser();
                }

                if (is_callable([$parser, 'parse'])) {
                    $document = $parser->parse($text);

                    // If the document can be converted to array, use that
                    if (is_array($document)) {
                        return $document;
                    }

                    if (is_object($document)) {
                        if (method_exists($document, 'toArray')) {
                            return $document->toArray();
                        }

                        if (method_exists($document, 'toJson')) {
                            $json = $document->toJson();
                            $decoded = json_decode($json, true);
                            if (json_last_error() === JSON_ERROR_NONE) {
                                return $decoded;
                            }
                        }

                        if (method_exists($document, 'getLines')) {
                            $lines = [];
                            foreach ($document->getLines() as $line) {
                                if (is_array($line)) {
                                    $lines[] = $line;
                                } elseif (is_object($line) && method_exists($line, 'toArray')) {
                                    $lines[] = $line->toArray();
                                } else {
                                    $lines[] = (string)$line;
                                }
                            }
                            return ['lines' => $lines];
                        }

                        // Fallback: string cast
                        return ['text' => (string)$document];
                    }
                }
            } catch (\Throwable $e) {
                // ignore and fall back to built-in parser below
            }
        }

        // Simple fallback parser:
        $lines = preg_split('/\r?\n/', $text);
        $result = [ 'lines' => [] ];

        foreach ($lines as $line) {
            $line = rtrim($line, "\r\n");
            if ($line === '') {
                $result['lines'][] = ['raw' => '', 'segments' => []];
                continue;
            }

            $segments = [];
            $pattern = '/\[([^\]]+)\]/';
            preg_match_all($pattern, $line, $matches, PREG_OFFSET_CAPTURE);

            if (empty($matches[0])) {
                $segments[] = ['text' => $line];
            } else {
                $cursor = 0;
                foreach ($matches[0] as $i => $m) {
                    $mFull = $m[0];
                    $mPos  = $m[1];
                    $chord  = $matches[1][$i][0];

                    // text before chord
                    $textBefore = substr($line, $cursor, $mPos - $cursor);
                    if ($textBefore !== '') {
                        $segments[] = ['text' => $textBefore];
                    }

                    // chord token
                    $segments[] = ['chord' => $chord];

                    $cursor = $mPos + strlen($mFull);
                }

                // trailing text
                $rest = substr($line, $cursor);
                if ($rest !== '') {
                    $segments[] = ['text' => $rest];
                }
            }

            $result['lines'][] = ['raw' => $line, 'segments' => $segments];
        }

        return $result;
    }
}
