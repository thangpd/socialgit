<div class="lema-article-wrapper lema-learning-content lema-quiz-test-st active" data-type="quiz-info">
  	<div class="lema-top-heading-bar">
	    <h3 class="lema-lesson-title"><?php echo $post->post_title?></h3>
	    <div class="lema-index-desc">
	      <span>Chapter <?php echo $chapter->post_title?></span>
	      <span><?php echo count($post->questions)?> <?php echo esc_html('Questions', 'education')?></span>
	    </div>
  	</div>
  <div class="lema-quiz-test-content">
      <div class="lema-form-group clear">
          <div class="lema-control-label"><span class="lema-label to-pass"><?php echo esc_html('Attempt:', 'education')?></span></div>
          <div class="lema-control-right">
              <strong><?php echo intval($post->attempt)?>% or higher</strong>
          </div>
      </div>
      <div class="lema-form-group clear">
          <div class="lema-control-label"><span class="lema-label deadline"><?php echo esc_html('Deadline:', 'education')?></span></div>
          <div class="lema-control-right">
              <strong><?php echo $post->deadline?></strong>
          </div>
      </div>
      <div class="lema-form-group clear">
          <div class="lema-control-label"><span class="lema-label attempt"><?php echo esc_html('Questions:', 'education')?></span></div>
          <div class="lema-control-right">
              <strong><?php echo count($post->questions)?> <?php echo esc_html('Questions', 'education')?></strong>
          </div>
      </div>
      <div class="lema-form-group clear">
          <div class="lema-control-label"><span class="lema-label estimate"><?php echo esc_html('Estimate time:', 'education')?></span></div>
          <div class="lema-control-right">
              <strong id="countdown" data-time="<?php echo intval($post->est_time)?>" class="convertToTime"><?php echo intval($post->est_time)?></strong>
          </div>
      </div>
  </div>
  <a href="#" id="btn_quiz_test" data-type="quiz-test" class="lema-btn lema-btn-primary"><?php echo esc_html('Start testing', 'education')?></a>
</div>

<div class="lema-article-wrapper lema-quiz-test lema-learning-content" data-type="quiz-test" >
    <div class="lema-top-heading-bar">
        <a href="#" class="lema-btn lema-btn-back" data-type="quiz-info"><i class="fa fa-arrow-left"></i></a>
        <h3 class="lema-lesson-title"><?php echo $post->post_title?></h3>
        <div class="lema-index-desc">
            <span>Chapter <?php echo $chapter->post_title?></span>
	      	<span><?php echo count($post->questions)?> <?php echo esc_html('Questions', 'education')?></span>
        </div>
        <div class="lema-time-left">
            <span class="lema-text"><?php echo esc_html('Time Left', 'education')?></span>
            <div><i class="fa fa-clock-o fa-fw"></i> <span class="count-time"><?php echo intval($post->est_time)?></span></div>
        </div>
    </div>

    <form class="quiz-question-form" data-submit_quiz method="post">
        <div class="mb-title">
          <h3 class="lema-lesson-title"><?php echo $post->post_title?></h3>
            <div class="lema-index-desc">
                <span>Chapter <?php echo $chapter->post_title?></span>
              <span><?php echo count($post->questions)?> <?php echo esc_html('Questions', 'education')?></span>
            </div>
        </div>
        <input type="hidden" name="quiz" value="<?php echo $post->ID?>">
        <div class="lema-quiz-list">
        <?php foreach($post->questions as $key=>$question){ ?>
            <div class="lema-quiz-item clear">
                <div class="point">
                    <span class="num">1</span>
                    <span class="desc"><?php echo esc_html('Point', 'education')?></span>
                </div>
                <div class="quiz-block">
                    <div class="question">
                        <span class="ques-num"><?php echo $key+1?>.</span>
                        <?php echo $question->post_content?>
                    </div>
                    <?php if(is_array($question->answer) && count($question->answer)){ ?>
                    <div class="answers">
                    	<?php foreach($question->answer as $keyAnswer=>$answer){ ?>
                        <div class="lema-form-group">
                            <label for="" class="lema-radio">
                                <input type="radio" name="question[<?php echo $question->ID?>]" value="<?php echo $keyAnswer?>">
                                <span class="lema-input-text"><?php echo $answer['content']?></span>
                            </label>
                        </div>
                    	<?php } ?>
                    </div>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
        </div>
        <div class="text-center">
    	    <!-- <ul class="lema-quiz-pagination">
    	    	<?php foreach($post->questions as $key=>$question){ ?>
    	        <li>
    	            <a href="#"><?php echo $key+1?></a>
    	        </li>
    	        <?php } ?>
    	    </ul>
    	    <br /><br /> -->
    	    <div class="lema-btn-group">
    	       <button type="submit" id="btn_quiz_result" class="lema-btn"><?php echo esc_html('Submit your test', 'education')?></button>
    	    </div>
    	    <br />
        </div>
    </form>
</div>
<div class="lema-article-wrapper lema-learning-content lema-quiz-result-page" id="result_quiz">
    
</div>