<?php



?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8">
    <meta content="IE=edge" http-equiv="X-UA-Compatible">
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <title>My eTutor - Student Application</title>
    
    <link rel="stylesheet" href="css/sat-parents.css">
    
    <link type="text/css" rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:400,400italic,600,600italic,700,700italic,800,800italic,300italic,300&amp;subset=greek-ext,greek,latin-ext,latin">

  </head>
  <body>

  	<div id="main-wrapper">
          
		    <header id="main-top">
	              
	                  <div id="logo"><img src="img/logo72.png"></div>
	                  <div id="mbmenu">
	                    <img src="img/menu-icon.png">
	                  </div>

	                  <div id="sm">
	                  	<a href="#"><img src="img/fb-icon.png"></a>
	                  	<a href="#"><img src="img/twitter-icon.png"></a>
	                  	<a href="#"><img src="img/gplus-icon.png"></a>
	                  </div>
	                  
	             
		    </header>


        	<div id="applycation-content">

        		<div id="leftside">
	        		<div id="usersteps">
	        			<h3 class="big">Student Application</h3>
	        			<div class="connector"></div>
	        			<ol>
	        				<li class="part1 active">Student information</li>
	        				<li class="part2">Parent Information</li>
	        				<li class="part3">Payment Information / Device</li>
	        			</ol>

	        		</div>
        		</div>
        		<div id="rightside">
        			<div id="userinputs">
                <h3 class="big part1">Student information (1 of 3)</h3>
                <h3 class="big part2">Student Information (2 of 3)</h3>
                <h3 class="big part3">Payment Information / Device (3 of 3)</h3>

        				<form action="applycationhandler.php" method="post" class="registration" name="regForm" id="app_form">
                      

                    
          				<div id="part1" class="part1">
          					  <input type="text" placeholder="Student's Name" name="firstname">
                      <input type="text" placeholder="Student's Surname" name="surname">
                      <input type="email" placeholder="Student's email" name="email" id="stemail">
                      <input type="email" placeholder="Parent's email" name="parentsemail">
                      <input type="radio" value="male" name="sex"><span class="sex male">Male</span>
                      <input type="radio" value="female" name="sex"><span class="sex female">Female</span>
                      <div class="clearfix"></div>
                      <select id="student_age" name="age">
                        <option value="default" selected>Age</option>
                        <option value="12">12</option>
                        <option value="13">13</option>
                        <option value="14">14</option>
                        <option value="15">15</option>
                        <option value="16">16</option>
                        <option value="17">17</option>
                        <option value="18">18</option>
                      </select>
                      <select id="school_level" name="school_level">
                        <option value="default" selected>School level</option>
                        <option value="7">7th Grade</option>
                        <option value="8">8th Grade</option>
                        <option value="9">9th Grade</option>
                        <option value="10">10th Grade</option>
                        <option value="11">11th Grade</option>
                        <option value="12">12th Grade</option>
                      </select>
                      <div class="clearfix"></div>
                      <input type="checkbox" name="stdevice1" value="1"><span class="chbox">Pc (Desktop)</span>
                      <input type="checkbox" name="stdevice2" value="1"><span class="chbox">Laptop</span>
                      <input type="checkbox" name="stdevice3" value="1"><span class="chbox">Tablet</span>
                      <input type="checkbox" name="stdevice4" value="1"><span class="chbox">Mobile</span>
                  
                      <!--<input type="text" name="birthdate" id="datepicker" placeholder="Birth Date">-->
                      
                      <button class="part1 formnext">Next</button>
                      
          				</div>

                  <div id="part2" class="part2">
                      <input type="text" placeholder="Parent's Name" name="parentfirstname">
                      <input type="text" placeholder="Parent's Surname" name="parentsurname">
                      <input type="radio" value="mother" name="parent"><span class="sex male">Mother</span>
                      <input type="radio" value="father" name="parent"><span class="sex female">Father</span>
                      <input type="radio" value="other" name="parent"><span class="sex female">Other</span> 
                      <input type="text" placeholder="Parent's email" name="parentsemail">
                      <input type="text" placeholder="Child's email" name="childsemail" id="stemailcopy" readonly>
                      <select id="num_child" name="numchild">
                        <option value="default" selected># of children</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5+</option>
                      </select>
                      <button class="part2 formprevious">back</button>
                      <button class="part2 formnext">Next</button>
                  </div>

                  <div id="part3" class="part3">
                      
                      <select id="paym_method" name="Preferred_payment_method">
                        <option value="default" selected>Subscription</option>
                        <option value="1">3 months subscription</option>
                        <option value="2">6 months subscription</option>
                        <option value="3">1 school year (9 months)</option>
                      </select>
                      <select id="paym_tip" name="Preferred_payment_tip">
                        <option value="default" selected>Preferred payment type</option>
                        <option value="1">CC</option>
                        <option value="2">Bank deposit</option>
                        <option value="3">Online Payment</option>
                        
                      </select>
                      <div class="clearfix"></div>
                      <input type="checkbox" name="paydevice1" value="1"><span class="chbox">Pc (Desktop)</span>
                      <input type="checkbox" name="paydevice2" value="1"><span class="chbox">Laptop</span>
                      <input type="checkbox" name="paydevice3" value="1"><span class="chbox">Tablet</span>
                      <input type="checkbox" name="paydevice4" value="1"><span class="chbox">Mobile</span>
                      
                      <input type="hidden" value="Parent" name="type">
                      <button class="part3 formprevious">back</button>
                      <input type="submit" value="Register" id="reg_submit">

                  </div>



                </form>

                <div id="reg_complete">
                    
                </div>


                
        			</div>
        		</div>
        	</div>

        	<footer>
              <div class="links">

                    <div class="quicklinks">

                        <h3>Quick links</h3>

                        <ul>

                          <li><a href="http://www.myetutor.org/" target="_blank">Myetutor</a></li>

                          <li><a href="http://www.myetutor.org/about-us.php" target="_blank">About Us</a></li>

                          <li><a href="http://www.myetutor.org/student.php" target="_blank">About Students</a></li>

                          <li><a href="http://www.myetutor.org/parent.php" target="_blank">About Parents</a></li>

                          <li><a href="http://www.myetutor.org/teacher.php" target="_blank">About Tutors</a></li>

                        </ul>

                    </div>

                    <div class="contactus">

                      <h3>Contact us</h3>

                        <ul>

                          <li>Email : <a href="mailto:info@myetutor.org">info@myetutor.org</a></li>

                          <li>Phone UK : +44 (0)789 906-5543</li>

                          <li>Phone GR : +30 698058-12-59</li>


                        </ul>

                    </div>

                  </div>
                  <div class="copyrights">&copy; 2014 Myetutor.org</div>
        	</footer>

    </div>
    
  	<script src="js/jquery-2.1.1.min.js"></script>
    <!--<script src="js/reghandler.js"></script>-->

    <script type="text/javascript">
      $(document).ready(function() {

          $("button.part1.formnext").on('click', function(e){

              e.preventDefault();


              $("#stemailcopy").val($("#stemail").val());
              $("#usersteps ol li.part1").removeClass("active");
              $("#usersteps ol li.part2").addClass("active");
              $("h3.part1").hide();
              $("div#part1").hide();
              
              $("h3.part2").fadeIn();
              $("div#part2").fadeIn();

          });

          $("button.part2.formnext").on('click', function(e){

              e.preventDefault();

              $("#usersteps ol li.part2").removeClass("active");
              $("#usersteps ol li.part3").addClass("active");
              $("h3.part2").hide();
              $("div#part2").hide();
              
              $("h3.part3").fadeIn();
              $("div#part3").fadeIn();

          });

          $("button.part2.formprevious").on('click', function(e){

              e.preventDefault();

              $("#usersteps ol li.part2").removeClass("active");
              $("#usersteps ol li.part1").addClass("active");
              $("h3.part2").hide();
              $("div#part2").hide();
              
              $("h3.part1").fadeIn();
              $("div#part1").fadeIn();

          });

          $("button.part3.formprevious").on('click', function(e){

              e.preventDefault();

              $("#usersteps ol li.part3").removeClass("active");
              $("#usersteps ol li.part2").addClass("active");
              $("h3.part3").hide();
              $("div#part3").hide();
              
              $("h3.part2").fadeIn();
              $("div#part2").fadeIn();

          });
       
              
      });
    </script>
  
  </body>
  </html>