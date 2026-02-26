# ChordPro Base

## Content Representations

### Songs

```
/songs.json
```

Example output:

```json
{
    "songs": {
        "data": {
            "songs/let-it-be": {
                "chordProURL": "https://chord-pro-base.ddev.site/songs/let-it-be.chordpro",
                "chordProURI": "songs/let-it-be.chordpro",
                "slug": "let-it-be",
                "num": 1,
                "title": "Let It Be",
                "uuid": "n4srubpbrpwifp0e"
            }
        }
    }
}
```

### Song

```
/songs/{slug}.chordpro
```

Returns ChordPro code as plain text.

### Sets

```
/sets.json
```

## Example output

```json
{
    "sets": {
        "data": {
            "sets/gd-2-marz": {
                "slug": "gd-2-marz",
                "title": "GD 2. März",
                "date": "2026-03-02",
                "songsCount": 1
            }
        }
    }
}
```

## API

### Get the list of all Songs

```
method: GET
url: {{chord_pro_base_url}}/api/songs
```

Example response

```json
{
    "code": 200,
    "data": [
        {
            "id": "songs/let-it-be",
            "num": 1,
            "title": "Let It Be",
            "url": "https://chord-pro-base.ddev.site/songs/let-it-be",
            "uuid": "page://n4srubpbrpwifp0e"
        },
        {
            "id": "songs/goodness-of-god",
            "num": 2,
            "title": "Goodness of God",
            "url": "https://chord-pro-base.ddev.site/songs/goodness-of-god",
            "uuid": "page://2slznqoqbbpyinju"
        }
    ],
    "pagination": {
        "page": 1,
        "total": 2,
        "offset": 0,
        "limit": 100
    },
    "status": "ok",
    "type": "collection"
}
```

### Get a specific Song

You can get a song in several formats.

```
method: GET
url: {{chord_pro_base_url}}/api/song/{{uuid}}/{{format}}
```

`uuid` without protocol (`page://`).

`format` can be one of

- `chordpro`
- `json`
- `text` (json with btext only)
- `html`
