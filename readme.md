# Text Parser Backend

## What is this?
This is a small demonstration. It's an API that exposes a single endpoint:

- /api/analysis/create

This endpoint accepts post requests containing a single field: a file upload. It should be named _upload_. If you upload a text file we attempt to figure out the most common word, surround it with _foo_ and _bar_, and hand it back to you in a json object.

The returned json has this format:

```
{
    "file": {
        "file_path": '', // laravel path to file with original content
        "file_location": '', // internal path to file with original content
        "file_url": '', // public url of file with original content
        "mime_type": '', // what it sounds like
        "size": '', // in bytes
        "file_modified": '', // laravel path to file with modified content
        "file_modified_url": '' // public url to file with modified content
    },
    "analysis": {
        "word_count": '', // total word count
        "unique_word_count": '', // unique word count
        "most_common_word": '' // the word we replace
    },
    "modified_text": '', // the entire modified text
    "modified_changes": '' // lowercase modified string
}
```

For each analysis we save two files: one with the original content, and one where the modifications has been made. The root contains a form you can use to generate this analysis, but I recommend using the Text Parser Frontend which is the decoupled frontend for this little app. There's a link to that repo below.

## Installation

This app is built on Laravel, which is a fantastic PHP framework. We don't use a database or any fancy bells and whistles, so all we require is what Laravel require:

- https://laravel.com/docs/5.8/installation

Part of that is composer, PHPs package manager:

- https://getcomposer.org/

Once you've got that taken care of, follow these steps:

- Clone the repository
- run `composer install`
- copy `.env.example` to `.env`
- Ensure `storage` and `bootstrap/cache` are writeable

And that should be it!

## Improvements

You can always make an app like this better. I stopped here, since I felt it demonstrated the general approach (decoupled backend/frontend, streaming files) well enough. If I wanted to continue working on it, here are some things I would focus on:

- Save analyses in DB. We already save files to drive, but if I saved the data in DB I would make it possible for a user to return to their analysis. This would be useful when we started working on...
- Queues. We currently do our analysis on a request. It works when we're dealing with smaller files, but larger will definitely time out. We can get around that by queueing them all and processing them in the background and send the user an e-mail when we're done.
- Error handling. We don't really give useful and well formatted errors right now.
- Improving the analysis. The tokenization isn't awesome, and that's what we base our entire analysis on. There are algorithms and stemming that helps with this, and with some research they could be implemented pretty easily, I think.

## Wait, who are you again?

I'm Linus! You can find me here:

- https://linusbohman.se

And the two repos for this app can be found here:

- https://github.com/bohman/text-parser-backend
- https://github.com/bohman/text-parser-frontend

Cheers! I hope you're having a great day!