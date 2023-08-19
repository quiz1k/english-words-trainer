@extends("index")
@section("content")
<body class="antialiased">

    <div class="row">
        <div class="col-md-4">
        </div>
    </div>

    <div class="h-100 d-flex align-items-center justify-content-center">
        <div class="row w-25">
            <h2>Answered words:</h2>
            <div class="col-md-9" id="answeredWords">

            </div>
        </div>
        <div class="w-25">
            <h1 style="display: none; color: red;" id="error"></h1>
            <h1 id="word">{{ $word }}</h1>
            <div class="input-group mb-3">
                <input id="answer" type="text" class="form-control" placeholder="Input translate" aria-label="Input translate" aria-describedby="basic-addon1">
                <input id="word_id" type="hidden" value="{{ $word_id }}">
            </div>
            <button type="button" class="btn btn-primary" onclick="test()">Send</button>
        </div>
    </div>

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

                    if ($("#answeredWords").find("*").length >= 15) {
                        $('#answeredWords').html('')
                    }

                    $('#answeredWords').append(result.html)
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
