<div class="row">


    {{ Form::open(array('url' => '/facebook/csv/loader/', 'files' => true)) }}
    <div class="col-lg-6 col-sm-6 col-12">
        <h4>Please upload you facebook lead csv and lets start the magic.</h4>
        <div class="input-group">
            <label class="input-group-btn">
                <span class="btn btn-primary">
                    Browse&hellip; <input name="csv" type="file" style="display: none;" multiple required>
                </span>
            </label>
            <input type="text" class="form-control" readonly>
            <input type="hidden" name="send" value="false">
        </div>
            <span class="help-block">
               Try selecting one or more files and watch the feedback
            </span>

        <div class="form-group">
            <button class="btn btn-primary">Extract</button>
        </div>
    </div>
    {{ Form::close() }}



</div>
<hr>