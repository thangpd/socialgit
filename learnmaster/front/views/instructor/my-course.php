<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */
 ?>
 <style>
 	.text-r {
 		text-align: right;
 	}
 	.text-c {
 		text-align: center;
 	}
 	.padding-50 {
 		padding-top: 50px;
 		padding-bottom: 50px;
 	}
 	.padding-100 {
 		padding-top: 100px;
 		padding-bottom: 100px;
 	}
 </style>
<div class="lema-page-content lema-instructor">
	<div class="container">
		<!-- Heading section-->
		<div class="heading-section">
			<div class="wrapper-heading">
				<div class="heading-title">Instructor Dashboard</div>
				<div class="heading-button text-r">
					<button class="button">Create New Course</button>
				</div>		
			</div>
		</div>
		<!-- End of Heading Section-->
		<!-- Tab -->
		<div class="lema-tabs padding-50">
			<div class="tab-list-wrapper">
				<ul class="tab-list">
					<li><a data-tab="1" class="tab-link active">Course</a></li>
					<li><a data-tab="2" class="tab-link">Q&a</a></li>
					<li><a data-tab="3" class="tab-link">Reviews</a></li>
					<li><a data-tab="4" class="tab-link">Assignments</a></li>
					<li><a data-tab="5" class="tab-link">Insights</a></li>
				</ul>
			</div>
			<div class="tab-content-wrapper">
				<div class="tab-panel active" data-content="1">
					<div class="lema-base-counter">
						<div class="wrapper-base-content">
							<div class="lema-columns lema-column-5">
								<div class="item item-base">
									<div class="sub-title">Total Revenue</div>
									<div class="count-number">$2,436</div>
								</div>
								<div class="item item-base">
									<div class="sub-title">Recent Average Rating</div>
									<div class="count-number">8.25</div>
								</div>
								<div class="item item-base">
									<div class="sub-title">Total Students</div>
									<div class="count-number">498</div>
								</div>
								<div class="item item-base">
									<div class="sub-title">Top Student Locations</div>
									<div class="flag-wrapper">
										<div class="item-flag">
											<img src="http://via.placeholder.com/28x20" alt="" title="">
										</div>
										<div class="item-flag">
											<img src="http://via.placeholder.com/28x20" alt="" title="">
										</div>
										<div class="item-flag">
											<img src="http://via.placeholder.com/28x20" alt="" title="">
										</div>
										<div class="item-flag">
											<img src="http://via.placeholder.com/28x20" alt="" title="">
										</div>
										<div class="item-flag">
											<img src="http://via.placeholder.com/28x20" alt="" title="">
										</div>
									</div>
								</div>
								<div class="item item-base">
									<div class="sub-title">Countries With Students</div>
									<div class="count-number">126</div>
								</div>
							</div>
						</div>
					</div>
					<div class="wrapper-heading padding-50">
						<div class="lema-form-group searching-area">
	                        <input type="text" class="lema-form-control" placeholder="Search lesson" />
	                        <button type="button" class="button button-search button-area">
	                        Search<i class="icons fa fa-search" aria-hidden="true"></i></button>
	                    </div>
	                    <div class="lema-form-group filtering-area text-r">
	                    	<label class="name">Sort by:</label>
	                    	<select class="option">
							  <option value="Most Relevant">Most Relevant</option>
							  <option value="Most Relevant 1">Most Relevant 1</option>
							  <option value="Most Relevant 2">Most Relevant 2</option>
							</select>
	                    </div>
					</div>
					<div class="lema-course-list view-course lema-course-row-list">
						<div class="lema-columns lema-column-1">
	                        <div class="item lema-course">
	                            <div class="course-item  ">
	                                <div class="course-wrapper">
	                                    <div class="top-wrapper course-image-wrapper">
	                                        <a href="" class="link">
	                                            <img src="http://wp.solazu.net/flexi/wp-content/uploads/2017/02/blog7-750x450.jpg" alt="" class="img-responsive img-full">
	                                        </a>
	                                    </div>
	                                    <div class="middle-wrapper course-content-wrapper">
	                                        <a href="" class="title-course">Design beautiful landing papges that generate quality leads</a>
	                                        <div class="description-course">
	                                            	Taking references from diverse mediums is key when coming up with new ideas for artworks whether illustration
                                        	</div>
	                                        <div class="reviews-wrapper">
		                                        <div class="status-course">
		                                        	<span class="public">Public</span>
		                                        </div>
		                                        <div class="lema-star-rating">
		                                            <div class="icon-star">
		                                                <fieldset class="rating">
		                                                    <input type="radio" id="star1" name="rating" value="1">
		                                                    <label style="color: #ffda21;" class="full" for="star1" title="Sucks big time - 1 star"></label>
		                                                    <input type="radio" id="star2" name="rating" value="2">
		                                                    <label style="color: #ffda21;" class="full" for="star2" title="Kinda bad - 2 stars"></label>
		                                                    <input type="radio" id="star3" name="rating" value="3">
		                                                    <label style="color: #ffda21;" class="full" for="star3" title="Meh - 3 stars"></label>
		                                                    <input type="radio" id="star4" name="rating" value="4">
		                                                    <label class="full" for="star4" title="Pretty good - 4 stars"></label>
		                                                    <input type="radio" id="star5" name="rating" value="5">
		                                                    <label class="full" for="star5" title="Awesome - 5 stars"></label>
		                                                </fieldset>
		                                            </div>
		                                            <div class="view-star">3.6<span>(25614)</span></div>
		                                        </div>
		                                        <div class="lema-discount">
		                                        	<div class="price-sale">$200</div>
			                                       	<div class="time-sale">$100</div>
			                                    </div>
	                                        </div>
	                                    </div>
	                                    <div class="footer-wrapper">
	                                    	<div class="item-curriculum">
	                                    		<div class="sub-item">Published Curriculum Items</div>
	                                    		<div class="number-item">
	                                    			13
	                                    			<span class="text">items</span>
	                                    		</div>
	                                    	</div>
	                                    	<div class="item-curriculum">
	                                    		<div class="sub-item">Total Curriculum Items</div>
	                                    		<div class="number-item">
	                                    			15
	                                    			<span class="text">items</span>
	                                    		</div>
	                                    	</div>
	                                    </div>
	                                </div>
	                            </div>
	                        </div>
	                        <div class="item lema-course">
	                            <div class="course-item  ">
	                                <div class="course-wrapper">
	                                    <div class="top-wrapper course-image-wrapper">
	                                        <a href="" class="link">
	                                            <img src="http://wp.solazu.net/flexi/wp-content/uploads/2017/02/blog7-750x450.jpg" alt="" class="img-responsive img-full">
	                                        </a>
	                                    </div>
	                                    <div class="middle-wrapper course-content-wrapper">
	                                        <a href="" class="title-course">Design beautiful landing papges that generate quality leads</a>
	                                        <div class="description-course">
	                                            	Taking references from diverse mediums is key when coming up with new ideas for artworks whether illustration
                                        	</div>
	                                        <div class="reviews-wrapper">
		                                        <div class="status-course">
		                                        	<span class="draft">Draft</span>
		                                        </div>
		                                        <div class="lema-star-rating">
		                                            <div class="icon-star">
		                                                <fieldset class="rating">
		                                                    <input type="radio" id="star1" name="rating" value="1">
		                                                    <label style="color: #ffda21;" class="full" for="star1" title="Sucks big time - 1 star"></label>
		                                                    <input type="radio" id="star2" name="rating" value="2">
		                                                    <label style="color: #ffda21;" class="full" for="star2" title="Kinda bad - 2 stars"></label>
		                                                    <input type="radio" id="star3" name="rating" value="3">
		                                                    <label style="color: #ffda21;" class="full" for="star3" title="Meh - 3 stars"></label>
		                                                    <input type="radio" id="star4" name="rating" value="4">
		                                                    <label class="full" for="star4" title="Pretty good - 4 stars"></label>
		                                                    <input type="radio" id="star5" name="rating" value="5">
		                                                    <label class="full" for="star5" title="Awesome - 5 stars"></label>
		                                                </fieldset>
		                                            </div>
		                                            <div class="view-star">3.6<span>(25614)</span></div>
		                                        </div>
		                                        <div class="lema-discount">
			                                       	<div class="time-sale">Free</div>
			                                    </div>
	                                        </div>
	                                    </div>
	                                    <div class="footer-wrapper">
	                                    	<div class="item-curriculum">
	                                    		<div class="sub-item">Published Curriculum Items</div>
	                                    		<div class="number-item">
	                                    			13
	                                    			<span class="text">items</span>
	                                    		</div>
	                                    	</div>
	                                    	<div class="item-curriculum">
	                                    		<div class="sub-item">Total Curriculum Items</div>
	                                    		<div class="number-item">
	                                    			15
	                                    			<span class="text">items</span>
	                                    		</div>
	                                    	</div>
	                                    </div>
	                                </div>
	                            </div>
	                        </div>
							<div class="item lema-course">
	                            <div class="course-item  ">
	                                <div class="course-wrapper">
	                                    <div class="top-wrapper course-image-wrapper">
	                                        <a href="" class="link">
	                                            <img src="http://wp.solazu.net/flexi/wp-content/uploads/2017/02/blog7-750x450.jpg" alt="" class="img-responsive img-full">
	                                        </a>
	                                    </div>
	                                    <div class="middle-wrapper course-content-wrapper">
	                                        <a href="" class="title-course">Design beautiful landing papges that generate quality leads</a>
	                                        <div class="description-course">
	                                            	Taking references from diverse mediums is key when coming up with new ideas for artworks whether illustration
                                        	</div>
	                                        <div class="reviews-wrapper">
		                                        <div class="status-course">
		                                        	<span class="hidden">Hidden</span>
		                                        </div>
		                                        <div class="lema-star-rating">
		                                            <div class="icon-star">
		                                                <fieldset class="rating">
		                                                    <input type="radio" id="star1" name="rating" value="1">
		                                                    <label style="color: #ffda21;" class="full" for="star1" title="Sucks big time - 1 star"></label>
		                                                    <input type="radio" id="star2" name="rating" value="2">
		                                                    <label style="color: #ffda21;" class="full" for="star2" title="Kinda bad - 2 stars"></label>
		                                                    <input type="radio" id="star3" name="rating" value="3">
		                                                    <label style="color: #ffda21;" class="full" for="star3" title="Meh - 3 stars"></label>
		                                                    <input type="radio" id="star4" name="rating" value="4">
		                                                    <label class="full" for="star4" title="Pretty good - 4 stars"></label>
		                                                    <input type="radio" id="star5" name="rating" value="5">
		                                                    <label class="full" for="star5" title="Awesome - 5 stars"></label>
		                                                </fieldset>
		                                            </div>
		                                            <div class="view-star">3.6<span>(25614)</span></div>
		                                        </div>
		                                        <div class="lema-discount">
		                                        	<div class="price-sale">$100</div>
			                                       	<div class="time-sale">$20</div>
			                                    </div>
	                                        </div>
	                                    </div>
	                                    <div class="footer-wrapper">
	                                    	<div class="item-curriculum">
	                                    		<div class="sub-item">Published Curriculum Items</div>
	                                    		<div class="number-item">
	                                    			13
	                                    			<span class="text">items</span>
	                                    		</div>
	                                    	</div>
	                                    	<div class="item-curriculum">
	                                    		<div class="sub-item">Total Curriculum Items</div>
	                                    		<div class="number-item">
	                                    			15
	                                    			<span class="text">items</span>
	                                    		</div>
	                                    	</div>
	                                    </div>
	                                </div>
	                            </div>
	                        </div>
	                        <div class="text-c"><button class="button">VIEW MORE</button></div>
        				</div>
						</div>
				</div>
				<div class="tab-panel" data-content="2">

				</div>
			</div>
		</div>
		<!-- End of Tab -->
	</div>
</div>