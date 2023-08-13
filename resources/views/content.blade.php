@extends("index")
@section("content")
<body class="antialiased">
<h1 style="display: none;" id="error"></h1>
<div class="row">
    <div class="col-md-9" id="answeredWords">

    </div>
</div>
<h1 id="word">{{ $word }}</h1>
<label for="answer">Input translate</label>
<input id="answer" type="text">
<input id="word_id" type="hidden" value="{{ $word_id }}">
<button onclick="test()">Next</button>
</body>
@endsection

<script>

    function test() {
        var answer = $('#answer').val()
        var wordId = $('#word_id').val()
        $.ajax({
            url: '/send/word',
            data: {
                '_token': $('meta[name="csrf-token"]').attr('content'),
                'word_id': wordId,
                'answer': answer
            },
            method: 'POST',
            success: function (result) {
                if (!result.error) {
                    $('#error').css('display', 'none')
                    console.log(result)
                    $('#answer').val('')
                    $('#word').html(result.newWord)
                    $('#word_id').val(result.word_id)
                    if (result.answerRight) {
                        $('#answeredWords').append('<p style="color: green">' + result.oldWord + '</p>')
                    } else {
                        $('#answeredWords').append('<p style="color: red">' + result.oldWord + '</p>')
                    }
                } else {
                    $('#error').html(result.error)
                    $('#error').css('display', 'block')
                }
            },
            error: function (error) {
                console.log(error.responseJSON.message)
                $('#error').html(error.responseJSON.message)
                $('#error').css('display', 'block')
            }
        })
    }

</script>
