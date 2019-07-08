<div class="lema-quiz-result-content">
    <?php if( $success_percent >= (intval($quiz->attempt) ) ){ ?>
    <div class="lema-icon icon-success"></div>
    <div class="lema-tile">You passed !</div>
    <?php }else{ ?>
    <div class="lema-icon icon-fail"></div>
    <div class="lema-tile">You are not pass !</div>
    <?php } ?>
    <div class="result-desc">You scored <b><?php echo $success_percent?>%</b>, but you need <b><?php echo 100-$success_percent?>%</b> to pass. You can retake the quiz as many times as you’d like.</div>

    <div class="lema-quiz-time">
        <div class="lm-item ques-num">
            <strong><?php echo $total_questions?></strong> Questions
        </div>
        <div class="lm-item time">
            Take time: <strong class="convertToTime"><?php echo $take_time?></strong>
        </div>
        <div class="lm-item time">
           Estimate time: <strong class="convertToTime"><?php echo $quiz->est_time?></strong>
        </div>
    </div>
    <div class="lema-score-detail">
        <div class="lema-item correct">
            <span class="num"><?php echo $correct?></span>
            <span class="text">Correct</span>
        </div>
        <div class="lema-item wrong">
            <span class="num"><?php echo $total_questions - $correct - $empty?></span>
            <span class="text">Wrong</span>
        </div>
        <div class="lema-item empty">
            <span class="num"><?php echo $empty?></span>
            <span class="text">Empty</span>
        </div>
        <div class="lema-item done">
            <span class="num"><?php echo $success_percent?>%</span>
            <span class="text">Done</span>
        </div>
    </div>
    <div class="lema-form-group">
        <button type="button" class="lema-btn" data-type="quiz-test">Retake Quiz</a>
    </div>
    <?php if(count($history_submit)){ ?>
    <table class="lema-table lema-table-score">
        <thead>
        <tr>
            <th># ID</th>
            <th>Time</th>
            <th>Take time</th>
            <th>Done</th>
            <th class="rm-action"></th> 
        </tr>
        </thead>
        <tbody>
            <?php $stt=1; foreach($history_submit as $key=>$his){ ?>
            <tr>
                <td data-title="# ID">
                    <span class="num"><?php echo $stt++?></span>
                </td>
                <td data-title="Time">
                    <strong class="date"><?php echo date('Y-m-d',$key)?></strong>
                    <span class="time"><?php echo date('H:i:s',$key)?></span>
                </td>
                <td data-title="Take time">
                    <span class="convertToTime"><?php echo $his['take_time']?></span>
                </td>
                <td data-title="Done">
                    <?php if($his['success_percent'] >= $quiz->attempt){
                        $class = 'correct';
                    }else if($his['success_percent'] > 0 ){
                        $class = 'wrong';
                    }else{
                        $class = 'empty';
                    }
                    ?>
                    <span class="percent-done <?php echo $class?>"><span class="num"><?php echo $his['success_percent']?>%</span></span>
                </td>
                <td data-title="Delete" class="rm-action">
                    <button class="lema-btn btn lema-btn-remove" data-remove_history="<?php echo $key?>"><i class="fa fa-trash"></i> </button>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
    <?php } ?>
</div>