<?php

namespace AndiMeier\ChordPro\Services;

use Kirby\Cms\File;
use Kirby\Cms\Page;
use Kirby\Cms\Url;
use Kirby\Exception\InvalidArgumentException;

class ChordProFileImporter
{
    public static function importFromUpload(File $file): void
    {
        if (!in_array($file->extension(), ['chordpro', 'chopro'])) {
            return;
        }

        $parent = $file->parent();
        if (!($parent instanceof Page) || $parent->intendedTemplate()->name() !== 'songs') {
            return;
        }

        $content = file_get_contents($file->root());
        $title   = static::extractTitle($content) ?? $file->name();
        $slug    = Url::slug($title);

        if ($parent->findPageOrDraft($slug) !== null) {
            $file->delete();
            throw new InvalidArgumentException(
                message: 'A song with the slug "' . $slug . '" already exists. Rename the file or change the {title:} directive.'
            );
        }

        $newPage = $parent->createChild([
            'slug'     => $slug,
            'template' => 'song',
            'content'  => [
                'title'        => $title,
                'chordprocode' => $content,
            ],
        ]);

        $newPage->changeStatus('listed');
        $file->delete();
    }

    private static function extractTitle(string $content): ?string
    {
        if (preg_match('/\{title:\s*(.+?)\s*\}/i', $content, $matches)) {
            return trim($matches[1]);
        }
        return null;
    }
}
