<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <meta charset="utf-8">
        <title>My eTutor</title>
        <meta name="generator" content="Bootply" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <link href="css/bootstrap.min.css" rel="stylesheet">

        <link href='css/fullcalendar.css' rel='stylesheet' />
        <link href='css/fullcalendar.print.css' rel='stylesheet' media='print' />
        <link href='http://fonts.googleapis.com/css?family=Roboto&subset=latin,latin-ext' rel='stylesheet' type='text/css'>

        <link rel="stylesheet" href="css/isotope.css">

        <link href='css/jquery.dataTables.css' rel="stylesheet"/>
        <!--[if lt IE 9]>
        <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        <link href="css/styles.css" rel="stylesheet">
        <link href="css/datepicker3.css" rel="stylesheet">


        <script src='js/lib/jquery.min.js'></script>
        <script src="js/jquery.isotope.js" type="text/javascript"></script> 
        <script src="js/countdown.js" type="text/javascript"></script>

    </head>
    <body>
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Enrollment Information</h4>
      </div>
      <div class="modal-body">
        <p style="margin: 10px;">Upon clicking the "Enroll" button a message is sent to the tutor's calendar. The tutor needs to confirm the enrollment. Upon confirmation the lesson appears in both student and tutor's calendar. </p>
      </div>
      
    </div>
  </div>
</div>
        <div class="wrapper">
            <a href="#" id="top"></a>
            <div class="box">
                <div class="row row-offcanvas row-offcanvas-left">
                    <!-- main right col -->
                    <div class="column col-sm-12 col-xs-12" id="main">

                        <!-- top nav -->
                        <div id="topside">
                            <div id="logo">
                                <img title="Myetutor" src="images/logo72.png">
                            </div>
                            <h2 id="toptitle">Schedule Dashboard</h2>
                            <div id="acc_container">
                                <a data-target="#unavailable" data-toggle="modal" href="#"><img title="Profile Avatar" src="images/pink-balloon.jpg"></a>
                                <p>Welcome Tutor</p>
                                <button onclick="window.location.href = 'doTheLogOut.php';" id="logout">Logout</button>
                            </div>
         
                    
                        </div>
                        <!-- /top nav -->

                        <div class="padding">
                            <div class="full col-sm-9">

                                <!-- content -->
                                <div class="row">


                                    <!-- main col right -->
                                    <div class="col-sm-8">
                                        <div class="well wboxed prounded">
                                            <ul id="myTab" class="nav nav-tabs">

                                                <li>
                                                    <a href="#mcal" role="tab" data-toggle="tab"><h3><span class="glyphicon glyphicon-calendar"></span> My Calendar</h3></a>
                                                </li>
                                                <li >
                                                    <a href="#reports" role="tab" data-toggle="tab"><h3><span class="glyphicon glyphicon-stats"></span> Reports & Statistics</h3></a>
                                                </li>
                                                <li>
                                                    <a href="#resources" role="tab" data-toggle="tab"><h3><span class="glyphicon glyphicon-book"></span> My Resource Library</h3></a>
                                                </li>
                                               
                                                <li>
                                                    <a href="#home" role="tab" data-toggle="tab"><h3><span class="glyphicon glyphicon-user"></span> My Profile</h3></a>
                                                </li>
                                            </ul>
                                            <div id="myTabContent" class="tab-content">
                                                <div class="tab-pane fade" id="reports">
                                                    <div class="panel panel-default intabed">
                                                        <div class="panel-body">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="panel panel-default intabed">
                                                                        <div class="panel-heading pstats"><h5><strong>Subject Knowledge Ranking vs original competence ranking</strong> </h5></div>
                                                                        <div class="panel-body">
                                                                            <div>
                                                                                <canvas id="canvasrad" height="350" width="450">

                                                                                </canvas>
                                                                            </div>
                                                                            <table>
                                                                                <tr><td style="background:rgba(151,187,205,1); padding-bottom: 5px;">&nbsp;&nbsp;&nbsp;</td><td>Current Knowledge Ranking</td></tr>
                                                                                <tr><td style="background:rgba(220,220,220,1);">&nbsp;&nbsp;&nbsp;</td><td>Original Competence Ranking</td></tr>
                                                                            </table>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="panel panel-default intabed">
                                                                        <div class="panel-heading pstats"><h5><strong>Growth Percentage vs your peers</strong></h5></div>
                                                                        <div class="panel-body">
                                                                            <div>
                                                                                <canvas id="canvas-line" height="350" width="450"></canvas>
                                                                            </div>
                                                                            <table>
                                                                                <tr><td style="background:rgba(151,187,205,1); padding-bottom: 5px;">&nbsp;&nbsp;&nbsp;</td><td>Your Growth</td></tr>
                                                                                <tr><td style="background:rgba(220,220,220,1);">&nbsp;&nbsp;&nbsp;</td><td>Your Peers' average Growth</td></tr>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-5">
                                                                    <div class="panel panel-default intabed">
                                                                        <div class="panel-heading pstats">
                                                                            <h5><strong>Knowledge Retention</strong></h5>
                                                                        </div>
                                                                        <div class="panel-body">
                                                                            <div class="circle" id="circles-1"></div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-7">
                                                                    <div class="panel panel-default intabed">
                                                                        <div class="panel-heading pstats"><h5><strong>Your Ranking among your peers</strong></h5></div>
                                                                        <div class="panel-body">
                                                                            <canvas id="canvasbar" height="350" width="600"></canvas>
                                                                            <table>
                                                                                <tr><td style="background:rgba(151,187,205,1); padding-bottom: 5px;">&nbsp;&nbsp;&nbsp;</td><td>Your ranking</td></tr>
                                                                                <tr><td style="background:rgba(220,220,220,1);">&nbsp;&nbsp;&nbsp;</td><td>Your Peers' Average Ranking</td></tr>
                                                                            </table>

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="tab-pane fade" id="mcal">
                                                    <div class="panel panel-default intabed">
                                                        <div class="panel-body">


                                                            <div class="panel panel-default intabed">
                                                                <div class="panel-heading scheduler">
                                                                    <span class="widgetTitle">Schedule your Lessons</span>
                                                                    <a id="schedule" class="pull-right widwhite" rel="popover" data-placement="right" data-content="Students when first join MyeTutor.org are required to sit a competence test to identify their initial knowledge level and possible educational gaps. They are also required to sit a psychometric test to identify their learning profile which can be one or a combination of any amongst Visual, Auditory, Read/write, or Kinaesthetic. Based on their psychometric profiles we then proceed into a deeper segmentation such as True Believers, Online Rejecters, Experience Seekers, Money Mavens, and Open Minds. 
                                                                       Similarly Tutors interested to join our eLearning platform are required to sit a comprehensive and challenging competence test as well as a psychometric test.
                                                                       Subsequently our knowledge engines take over and build the first content the student will be taught. They monitor her performance and dynamically adapt the content as she proceeds though her class. Content id dynamically adapted based on her performance, learning style, and knowledge level.
                                                                       In similar terms when a student requests a one-two-one tutoring the systems identifies tutors that are available that date, time and for the subject the student is interest to have a private class. They also proceed to match the student with tutors with similar personalities. Those are automatically identified through the psychometric profiles the engine has stored after Tutors and students taken their psychometric test during their initial registration. Such match ensures greater satisfaction and more fulfilling tutoring experience. Finally Tutors are also listed in order of satisfaction ranking they previously received by students in previous classes.
                                                                       Similar principles apply when a student selects to attend a class with up to 4 more students."><span class="glyphicon glyphicon-info-sign"></span>
                                                                    </a>
                                                                </div><div class="panel-body">
                                                                    <div class="row">
                                                                        <div id="schedwrapper">                                                                    
                                                                            <label>Enter the date for requesting tutor availability: </label>
                                                                            <div class="input-group date" id="calselect">

                                                                                <input type="text" class="form-control"><span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                                &nbsp;&nbsp;<button class="btn btn-default" name="submit" id="submit">Submit</button>
                                                                            </div>
                                                                        </div>



                                                                        <div class="col-md-5 col-md-offset-5" id="loading" style="padding:40px;"><img src="img/ajax-loader.gif"> <br> Fetching Results</div>
                                                                        <div id="results">
                                                                            <div class="row" style="margin-left: -30px;">
                                                                            <div class="col-md-12">
                                                                                <div class="col-md-8">
                                                                                    <h3>Tutors and Lessons Available for: <span id="tutlessons_available" style="color: #ff6160;"></span></h3>
                                                                                </div>
                                                                                <div class="col-md-4">
                                                                                <a href="sprofile.php#mcal" class="btn btn-default pull-right" style="margin-top: 15px;">Switch to Calendar View</a>
                                                                                </div>
                                                                            </div>
                                                                            </div>
                                                                            <hr>
                                                                            <div id="nav2" class="row" style="padding-bottom: 30px;">
                                                                                <div id="subject2" >
                                                                                    <strong>Filter by Subject:</strong>
                                                                                    <a href="#" data-filter="*" class="current">All subjects</a> -
                                                                                    <a href="#" data-filter=".algebra">Algebra</a> -
                                                                                    <a href="#" data-filter=".geometry">Geometry</a> -
                                                                                    <a href="#" data-filter=".functions">Functions</a> -
                                                                                    <a href="#" data-filter=".fractions">Fractions</a> 
                                                                                </div>
                                                                                <div id="topic2" >
                                                                                    <strong>Filter by Topic:</strong>
                                                                                    <a href="#" data-filter="*" class="current">All topics</a> -
                                                                                    <a href="#" data-filter=".algebra1">Algebra I</a> -
                                                                                    <a href="#" data-filter=".algebra2">Algebra II</a> -
                                                                                    <a href="#" data-filter=".geometry1">Circles</a> -
                                                                                    <a href="#" data-filter=".geometry2">Angles</a> -
                                                                                    <a href="#" data-filter=".functions1">Function Basics</a> -
                                                                                    <a href="#" data-filter=".fractions1">Fractions Ordering</a> 
                                                                                </div>
                                                                                <div id="tutors2" >
                                                                                    <strong>Filter by Tutor:</strong>
                                                                                    <a href="#" data-filter="*" class="current">All tutors</a> -
                                                                                    <a href="#" data-filter=".favored">Favored</a> -
                                                                                    <a href="#" data-filter=".followed">Followed</a> 

                                                                                </div>
                                                                            </div>
                                                                            <div class="row">
                                                                                <div id="content2">
                                                                                    
                                                                                    <div class="team algebra algebra1 geometry geometry1">
                                                                                        <div class="teamhead">

                                                                                         <div class="ava">
                                                                                            <img alt="team 1" src="img/teacher3.png" style="width: 195px; height: 195px;">
                                                                                        </div>
                                                                                        <div class="info">
                                                                                            <h4 class="name"><a href="tprofile_ext.php?name=Jen">Jen Peterson</a><small> - Math Tutor</small></h4>
                                                                                            <div class="sRating" style="padding-bottom: 5px;">
                                                            <img class="starRate" style="margin-left: -2px;" src="img/star_rating.png">   
                                                        </div>
                                                                                            <span style="color:#555; font-size:14px; font-weight:bold;">Matching your learning profile:</span> 
                                                                                            
                                                                                            <div class="progress">
                                                                                                <div class="progress-bar"></div>
                                                                                            </div>
                                                                                            
                                                                                        </div>
                                                                                        </div>
                                                                                        <div class="teamclasses">
                                                                                           
                                                                                            
                                                                                        <table class="table table-condensed nomar">
                                                                                            <thead>
                                                                                            <th>Time</th>
                                                                                            <th>Title</th>
                                                                                            <th>Topic/Subject</th>
                                                                                            <th>Type</th>
                                                                                            <th>Actions</th>
                                                                                            </thead>
                                                                                            <tbody>
                                                                                                <tr><td>17:30</td>
                                                                                                    <td><strong>Integers 101</strong></td>
                                                                                                    <td>Algebra I/Algebra</td>
                                                                                                    <td>Team Tutoring</td>
                                                                                                    <td><a href="#" data-toggle="modal" data-target="#myModal">Enroll</a></td>
                                                                                                </tr>
                                                                                                <tr><td>19:00</td>
                                                                                                    <td><strong>Circles Basics</strong></td>
                                                                                                    <td>Circles/Geometry</td>
                                                                                                    <td>Private Tutoring</td>
                                                                                                   <td><a href="#" data-toggle="modal" data-target="#myModal">Enroll</a></td>
                                                                                                </tr>
                                                                                            </tbody>
                                                                                        </table>
                                                                                               
                                                                                    </div>
                                                                                    </div>

                                                                                    <div class="team algrebra algebra1 geometry geometry1 favored followed">
                                                                                        <div class="teamhead">

                                                                                         <div class="ava">
                                                                                            <img alt="team 1" src="img/team2.png">
                                                                                        </div>
                                                                                        <div class="info">
                                                                                            <h4 class="name"><a href="tprofile_ext.php?name=Michael">Michael Smith</a><small> - Math Tutor</small></h4>
                                                                                            
                                                                                        <div class="sRating" style="padding-bottom: 5px;">
                                                            <img class="starRate" style="margin-left: -2px;" src="img/star_rating.png">   
                                                        </div>
                                                                                            <span style="color:#555; font-size:14px; font-weight:bold;">Matching your learning profile:</span> 
                                                                                            
                                                                                                <div class="progress">
                                                                                            <div class="progress-bar1"></div>
                                                                                            </div>
                                                                                        
                                                                                            <div class="social">
					<span class="glyphicon glyphicon-heart"></span> <span class="glyphicon glyphicon-star"></span>
				</div>
                                                                                        </div>
                                                                                        </div>
                                                                                        <div class="teamclasses">
                                                                                        <table class="table table-condensed nomar">
                                                                                            <thead>
                                                                                            <th>Time</th>
                                                                                            <th>Title</th>
                                                                                            <th>Topic/Subject</th>
                                                                                            <th>Type</th>
                                                                                            <th>Actions</th>
                                                                                            </thead>
                                                                                            <tbody>
                                                                                                <tr><td>11:30</td>
                                                                                                    <td><strong>Operations</strong></td>
                                                                                                    <td>Algebra I/Algebra</td>
                                                                                                    <td>Team Tutoring</td>
                                                                                                   <td><a href="#" data-toggle="modal" data-target="#myModal">Enroll</a></td>
                                                                                                </tr>
                                                                                                <tr><td>00:20</td>
                                                                                                    <td><strong>Arcs Basics</strong></td>
                                                                                                    <td>Angles/Geometry</td>
                                                                                                    <td>Private Tutoring</td>
                                                                                                   <td><a href="#" data-toggle="modal" data-target="#myModal">Enroll</a></td>
                                                                                                </tr>
                                                                                            </tbody>
                                                                                        </table>
                                                                                    </div>
                                                                                    </div>
                                                                                    
                                                                                    <div class="team functions functions1 favored">
                                                                                        <div class="teamhead">

                                                                                         <div class="ava">
                                                                                            <img alt="team 1" src="img/team1.png">
                                                                                        </div>
                                                                                        <div class="info">
                                                                                            <h4 class="name"><a href="tprofile_ext.php?name=George">George Hannes</a><small> - Math Tutor</small></h4>
                                                                                            
                                                                                        <div class="sRating" style="padding-bottom: 5px;">
                                                            <img class="starRate" style="margin-left: -2px;" src="img/star_rating.png">   
                                                        </div>
                                                                                            <span style="color:#555; font-size:14px; font-weight:bold;">Matching your learning profile:</span> 
                                                                                            
                                                                                                <div class="progress">
                                                                                            <div class="progress-bar3"></div>
                                                                                            </div>
                                                                                        
                                                                                            <div class="social">
					<span class="glyphicon glyphicon-heart"></span> 				</div>
                                                                                        </div>
                                                                                        </div>
                                                                                        <div class="teamclasses">
                                                                                        <table class="table table-condensed nomar">
                                                                                            <thead>
                                                                                            <th>Time</th>
                                                                                            <th>Title</th>
                                                                                            <th>Topic/Subject</th>
                                                                                            <th>Type</th>
                                                                                            <th>Actions</th>
                                                                                            </thead>
                                                                                            <tbody>
                                                                                                <tr><td>12:30</td>
                                                                                                    <td><strong>Function Operations</strong></td>
                                                                                                    <td>Basics/Functions</td>
                                                                                                    <td>Team Tutoring</td>
                                                                                                   <td><a href="#" data-toggle="modal" data-target="#myModal">Enroll</a></td>
                                                                                                </tr>
                                                                                                
                                                                                            </tbody>
                                                                                        </table>
                                                                                    </div>
                                                                                    </div>
                                                                                    
                                                                                    
                                                                                    <div class="team algrebra algebra1 geometry geometry2 followed">
                                                                                        <div class="teamhead">

                                                                                         <div class="ava">
                                                                                            <img alt="team 1" src="img/team4.png">
                                                                                        </div>
                                                                                        <div class="info">
                                                                                            <h4 class="name"><a href="tprofile_ext.php?name=Laura">Laura Barnes</a><small> - Math Tutor</small></h4>
                                                                                            
                                                                                        <div class="sRating" style="padding-bottom: 5px;">
                                                            <img class="starRate" style="margin-left: -2px;" src="img/star_rating.png">   
                                                        </div>
                                                                                            <span style="color:#555; font-size:14px; font-weight:bold;">Matching your learning profile:</span> 
                                                                                            
                                                                                                <div class="progress">
                                                                                            <div class="progress-bar4"></div>
                                                                                            </div>
                                                                                        
                                                                                            <div class="social">
					 <span class="glyphicon glyphicon-star"></span>
				</div>
                                                                                        </div>
                                                                                        </div>
                                                                                        <div class="teamclasses">
                                                                                        <table class="table table-condensed nomar">
                                                                                            <thead>
                                                                                            <th>Time</th>
                                                                                            <th>Title</th>
                                                                                            <th>Topic/Subject</th>
                                                                                            <th>Type</th>
                                                                                            <th>Actions</th>
                                                                                            </thead>
                                                                                            <tbody>
                                                                                                <tr><td>17:30</td>
                                                                                                    <td><strong>Multipliers</strong></td>
                                                                                                    <td>Algebra I/Algebra</td>
                                                                                                    <td>Team Tutoring</td>
                                                                                                   <td><a href="#" data-toggle="modal" data-target="#myModal">Enroll</a></td>
                                                                                                </tr>
                                                                                                <tr><td>19:00</td>
                                                                                                    <td><strong>Circle Functions</strong></td>
                                                                                                    <td>Circles/Geometry</td>
                                                                                                    <td>Private Tutoring</td>
                                                                                                   <td><a href="#" data-toggle="modal" data-target="#myModal">Enroll</a></td>
                                                                                                </tr>
                                                                                            </tbody>
                                                                                        </table>
                                                                                    </div>
                                                                                    </div>
                                                                                    
                                                                                    
                                                                                    <div class="team algrebra algebra1 geometry geometry1 ">
                                                                                        <div class="teamhead">

                                                                                         <div class="ava">
                                                                                            <img alt="team 1" src="img/teacher2.png" style="width:195px; height:195px;">
                                                                                        </div>
                                                                                        <div class="info">
                                                                                            <h4 class="name"><a href="tprofile_ext.php?name=Tom">Tom Breier</a><small> - Math Tutor</small></h4>
                                                                                            
                                                                                        <div class="sRating" style="padding-bottom: 5px;">
                                                            <img class="starRate" style="margin-left: -2px;" src="img/star_rating.png">   
                                                        </div>
                                                                                            <span style="color:#555; font-size:14px; font-weight:bold;">Matching your learning profile:</span> 
                                                                                            
                                                                                                <div class="progress">
                                                                                            <div class="progress-bar5"></div>
                                                                                            </div>
                                                                                        
                                                                                            
                                                                                        </div>
                                                                                        </div>
                                                                                        <div class="teamclasses">
                                                                                        <table class="table table-condensed nomar">
                                                                                            <thead>
                                                                                            <th>Time</th>
                                                                                            <th>Title</th>
                                                                                            <th>Topic/Subject</th>
                                                                                            <th>Type</th>
                                                                                            <th>Actions</th>
                                                                                            </thead>
                                                                                            <tbody>
                                                                                                <tr><td>12:30</td>
                                                                                                    <td><strong>Integers Usage</strong></td>
                                                                                                    <td>Algebra I/Algebra</td>
                                                                                                    <td>Private Tutoring</td>
                                                                                                   <td><a href="#" data-toggle="modal" data-target="#myModal">Enroll</a></td>
                                                                                                </tr>
                                                                                                <tr><td>22:00</td>
                                                                                                    <td><strong>Angles Basics</strong></td>
                                                                                                    <td>Angles/Geometry</td>
                                                                                                    <td>Private Tutoring</td>
                                                                                                   <td><a href="#" data-toggle="modal" data-target="#myModal">Enroll</a></td>
                                                                                                </tr>
                                                                                            </tbody>
                                                                                        </table>
                                                                                    </div>
                                                                                    </div>
                                                                                    
                                                                                    
                                                                                    <div class="team fractions fractions1 favored followed">
                                                                                        <div class="teamhead">

                                                                                         <div class="ava">
                                                                                            <img alt="team 1" src="img/teacher.png" style="width:195px; height:195px;">
                                                                                        </div>
                                                                                        <div class="info">
                                                                                            <h4 class="name"><a href="tprofile_ext.php?name=Sam">Sam Kavolski</a><small> - Math Tutor</small></h4>
                                                                                            
                                                                                        <div class="sRating" style="padding-bottom: 5px;">
                                                            <img class="starRate" style="margin-left: -2px;" src="img/star_rating.png">   
                                                        </div>
                                                                                            <span style="color:#555; font-size:14px; font-weight:bold;">Matching your learning profile:</span> 
                                                                                            
                                                                                                <div class="progress">
                                                                                            <div class="progress-bar6"></div>
                                                                                            </div>
                                                                                        
                                                                                            <div class="social">
					<span class="glyphicon glyphicon-heart"></span> <span class="glyphicon glyphicon-star"></span>
				</div>
                                                                                        </div>
                                                                                        </div>
                                                                                        <div class="teamclasses">
                                                                                        <table class="table table-condensed nomar">
                                                                                            <thead>
                                                                                            <th>Time</th>
                                                                                            <th>Title</th>
                                                                                            <th>Topic/Subject</th>
                                                                                            <th>Type</th>
                                                                                            <th>Actions</th>
                                                                                            </thead>
                                                                                            <tbody>
                                                                                                <tr><td>17:30</td>
                                                                                                    <td><strong>Ordering Fractions</strong></td>
                                                                                                    <td>Fractions/Ordering</td>
                                                                                                    <td>Team Tutoring</td>
                                                                                                   <td><a href="#" data-toggle="modal" data-target="#myModal">Enroll</a></td>
                                                                                                </tr>
                                                                                                
                                                                                            </tbody>
                                                                                        </table>
                                                                                    </div>
                                                                                    </div>
                                                                                </div>

                                                                            </div>





                                                                        </div>
                                                                    </div>
                                                                </div>


                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="tab-pane fade" id="home">
                                                    <div class="panel panel-default intabed">
                                                        <div class="panel-body">
                                                            <div class="row">
                                                                <div class="col-md-3">
                                                                    <div class="thumbnail">
                                                                        <img src="img/student3.jpg">
                                                                    </div>



                                                                </div>
                                                                <div class="col-md-9">
                                                                    <p class="tname">
                                                                        Student: Jenny Roswell
                                                                    </p>
                                                                    <p class="tbio">
                                                                        Jenny possesses computer skills, she is an independent worker; self-disciplined, with good communication and creative thinking skills. She is responsible with good management skills. She is Conscientious with a strong sense of community.
                                                                        She has strong math and analytical skills.
                                                                    </p>


                                                                </div>

                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="panel panel-default intabed">
                                                                        <div class="panel-heading latest">
                                                                            <span class="swidgetTitle"><span class="glyphicon glyphicon-heart"></span> Tutors followed</span>&nbsp;&nbsp;
                                                                            <a href="#" id="tufoll" class="widwhite pull-right" rel="popover" data-placement="top" data-content="This widget displays a list of the tutors that the student has collaborated with and has decided to follow their activities within the MyeTutor Community. For each tutor the number of lessons that the student and the tutor collaborated is displayed."><span class="glyphicon glyphicon-info-sign"></span></a>
                                                                        </div>
                                                                        <div class="panel-body">

                                                                            <div class="media">
                                                                                <a class="pull-left" href="#">
                                                                                    <img class="media-object" data-src="holder.js/64x64" alt="64x64" src="img/teacher5.jpg" style="width: 64px; height: 64px;">
                                                                                </a>
                                                                                <div class="media-body">
                                                                                    <h4 class="media-heading"><a href="#" data-toggle="modal" data-target="#aurabanks">Aura Banks</a></h4>
                                                                                    You have had <strong>6</strong> lessons with this tutor
                                                                                </div>
                                                                            </div>
                                                                            <div class="media">
                                                                                <a class="pull-left" href="#">
                                                                                    <img class="media-object" data-src="holder.js/64x64" alt="64x64" src="img/teacher2.png" style="width: 64px; height: 64px;">
                                                                                </a>
                                                                                <div class="media-body">
                                                                                    <h4 class="media-heading"><a href="#" data-toggle="modal" data-target="#aurabanks">Tom Breier</a></h4>
                                                                                    You have had <strong>3</strong> lessons with this tutor </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>



                                                                </div>
                                                                <div class="col-md-8">

                                                                    <div class="panel panel-default intabed">
                                                                        <div class="panel-heading upcomclasses">

                                                                            <span class="swidgetTitle"><span class="glyphicon glyphicon-heart"></span> My Favorite Tutors</span> &nbsp;&nbsp; <a href="#" id="tufav" rel="popover" class="widwhite pull-right"data-placement="top" data-content="This widget displays a list of the tutors that the student has collaborated with and has marked them as one of his/her favorites. Upon clicking of the tutors name the tutor's profile page is displayed."><span class="glyphicon glyphicon-info-sign"></span> </a>
                                                                        </div>
                                                                        <div class="panel-body">

                                                                            <div class="media">
                                                                                <a class="pull-left" href="#">
                                                                                    <img class="media-object" data-src="holder.js/64x64" alt="64x64" src="img/teacher.png" style="width: 64px; height: 64px;">
                                                                                </a>
                                                                                <div class="media-body">
                                                                                    <h4 class="media-heading"><a href="#" data-toggle="modal" data-target="#aurabanks">Sam Kavolski</a></h4>
                                                                                    <strong>Favored for:</strong> <a data-target="#unavailable" data-toggle="modal" href=""> Algrebra I: Introduction to integers</a>
                                                                                </div>
                                                                            </div>
                                                                            <div class="media">
                                                                                <a class="pull-left" href="#">
                                                                                    <img class="media-object" data-src="holder.js/64x64" alt="64x64" src="img/teacher3.png" style="width: 64px; height: 64px;">
                                                                                </a>
                                                                                <div class="media-body">
                                                                                    <h4 class="media-heading"><a href="#" data-toggle="modal" data-target="#aurabanks">Jen Peterson</a></h4>
                                                                                    <strong>Favored for:</strong> <a data-target="#unavailable" data-toggle="modal" href=""> Algrebra II: Using integers</a> | <a data-target="#unavailable" data-toggle="modal" href=""> Algrebra III: Advanced integers</a> </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="tab-pane fade" id="resources">
                                                    <div class="panel panel-default intabed">

                                                        <div class="panel-body">
                                                            <div class="row">

                                                                <div class="col-md-12">

                                                                    <div class="panel panel-default intabed">
                                                                        <div class="panel-heading tclasses">
                                                                            <span class="widgetTitle">Resources & Material</span>
                                                                            <a id="resmat" class="pull-right widwhite" rel="popover" data-placement="bottom" data-content="This table displays all resources and material available to the student for the courses s/he participates."><span class="glyphicon glyphicon-info-sign"></span>
                                                                            </a>
                                                                        </div>
                                                                        <div class="panel-body">




                                                                            <div id="nav" class="row" style="padding-bottom: 30px;">
                                                                                <div id="subject" >
                                                                                    <strong>Filter by Subject:</strong>
                                                                                    <a href="#" data-filter="*" class="current">All subjects</a>
                                                                                    <a href="#" data-filter=".algebra">Algebra</a>
                                                                                    <a href="#" data-filter=".geometry">Geometry</a>
                                                                                    <a href="#" data-filter=".functions">Functions</a>
                                                                                    <a href="#" data-filter=".fractions">Fractions</a>
                                                                                </div>

                                                                                <div id="topic" >
                                                                                    <strong>Filter by Topic:</strong>
                                                                                    <a href="#" data-filter="*" class="current">All topics</a>
                                                                                    <a href="#" data-filter=".algebra1">Algebra I</a>
                                                                                    <a href="#" data-filter=".algebra2">Algebra II</a>
                                                                                    <a href="#" data-filter=".geometry1">Circles</a>
                                                                                    <a href="#" data-filter=".geometry2">Angles</a>
                                                                                    <a href="#" data-filter=".functions1">Function Basics</a>
                                                                                    <a href="#" data-filter=".fractions1">Fractions Ordering</a>
                                                                                </div>
                                                                            </div>

                                                                            <div id="content">
                                                                                <div class="algebra algebra1 thumbnail">
                                                                                    <a href="algebra_view.php#resources"><img src="img/lessons/algebra.png"></a>
                                                                                </div>
                                                                                <div class="geometry geometry1 thumbnail">
                                                                                    <a data-target="#unavailable2" data-toggle="modal" href="#"><img src="img/lessons/geometry1.png"></a>
                                                                                </div>
                                                                                <div class="algebra algebra2 thumbnail">
                                                                                    <a data-target="#unavailable2" data-toggle="modal" href="#"><img src="img/lessons/algebra2.png"></a>
                                                                                </div>
                                                                                <div class="functions functions1 thumbnail">
                                                                                    <a data-target="#unavailable2" data-toggle="modal" href="#"><img src="img/lessons/functions.png"></a>
                                                                                </div>
                                                                                <div class="fractions fractions1 thumbnail">
                                                                                    <a data-target="#unavailable2" data-toggle="modal" href="#"><img src="img/lessons/fractions.png"></a>
                                                                                </div>
                                                                                <div class="geometry geometry2 thumbnail">
                                                                                    <a data-target="#unavailable2" data-toggle="modal" href="#"><img src="img/lessons/geometry2.png"></a>
                                                                                </div>
                                                                            </div>



                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="tab-pane fade" id="echapters">
                                                    <div class="panel panel-default intabed">

                                                        <div class="panel-body">
                                                            <div class="row">

                                                                <div class="col-md-12">

                                                                    <div class="panel panel-default intabed">
                                                                        <div class="panel-heading upcomclasses">

                                                                            <span class="widgetTitle">eChapters</span> <a id="echap" rel="popover" class="pull-right widwhite" data-placement="right" data-content="This table displays all available echapters that the student can purchase. For each eChapter the subject, topic, publisher and price will be displayed. Furthermore the student will be able to filter the provided results."><span class="glyphicon glyphicon-info-sign"></span> </a>
                                                                        </div>
                                                                        <div class="panel-body">
                                                                            <div id="nav" class="row" style="padding-bottom: 30px;">


                                                                                <table id="example2" class="display" cellspacing="0" width="100%" >
                                                                                    <thead>
                                                                                        <tr>
                                                                                            <th>Title</th>
                                                                                            <th>Subject</th>
                                                                                            <th>Topic</th>
                                                                                            <th>Publisher</th>
                                                                                            <th>Date</th>
                                                                                            <th>Related Material</th>
                                                                                            <th>Purchase</th>

                                                                                        </tr>
                                                                                    </thead>


                                                                                    <tbody>
                                                                                        <tr>
                                                                                            <td>All about integers</td>
                                                                                            <td>Algebra</td>
                                                                                            <td>Algebra I</td>
                                                                                            <td>Willey International</td>
                                                                                            <td>12/08/2014</td>
                                                                                            <td><a href="#">Introduction to Integers</a><br>Integers 101</td>
                                                                                            <td><a href="#"><span class="glyphicon glyphicon-shopping-cart"></span></a></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td>The concepts of circles</td>
                                                                                            <td>Geometry</td>
                                                                                            <td>Circles</td>
                                                                                            <td>EG Publishers</td>
                                                                                            <td>23/08/2014</td>
                                                                                            <td><a href="#">Introduction to Integers</a><br>Integers 101</td>
                                                                                            <td><a href="#"><span class="glyphicon glyphicon-shopping-cart"></span></a></td>
                                                                                        </tr>


                                                                                        <tr>
                                                                                            <td>All about integers</td>
                                                                                            <td>Algebra</td>
                                                                                            <td>Algebra I</td>
                                                                                            <td>GL Publishers</td>
                                                                                            <td>19/03/2014</td>
                                                                                            <td><a href="#">Introduction to Integers</a><br>Integers 101</td>
                                                                                            <td><a href="#"><span class="glyphicon glyphicon-shopping-cart"></span></a></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td>Arcs Theory</td>
                                                                                            <td>Geometry</td>
                                                                                            <td>Arcs and Circles I</td>
                                                                                            <td>New House Publishers</td>
                                                                                            <td>28/08/2014</td>
                                                                                            <td><a href="#">Introduction to Arcs</a></td>
                                                                                            <td><a href="#"><span class="glyphicon glyphicon-shopping-cart"></span></a></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td>Using integers</td>
                                                                                            <td>Algebra</td>
                                                                                            <td>Algebra II</td>
                                                                                            <td>EG Publishers</td>
                                                                                            <td>31/08/2014</td>
                                                                                            <td><a href="#">Introduction to Integers</a></td>
                                                                                            <td><a href="#"><span class="glyphicon glyphicon-shopping-cart"></span></a></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td>Fractions in practice</td>
                                                                                            <td>Fractions</td>
                                                                                            <td>Fractions Basics</td>
                                                                                            <td>LP</td>
                                                                                            <td>23/07/2014</td>
                                                                                            <td><a href="#">Introduction to Fractions</a></td>
                                                                                            <td><a href="#"><span class="glyphicon glyphicon-shopping-cart"></span></a></td>
                                                                                        </tr>


                                                                                    </tbody></table>


                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                        </div>
                                    </div><!--/row-->
                                    <!-- main col left -->
                                    <div class="col-sm-4">

                                        <div class="panel panel-default panrounded">
                                            <div class="panel-heading upcomclasses headrounded">
                                                <span class="widgetTitle">Upcoming Class</span>
                                                <a id="upcoming" class="pull-right" rel="popover" data-placement="top" data-content="This widget displays the time remaining for the next lesson that the tutor teaches."><span class="glyphicon glyphicon-info-sign myglyph"></span></a>
                                            </div>
                                            <div class="panel-body">
                                                <tablE>
                                                    <tr>
                                                        <td>
                                                            <script type="application/javascript">
                                                                var myCountdown2 = new Countdown({
                                                                time : 1500,
                                                                width : 200,
                                                                height : 60,
                                                                rangeHi : "hour",

                                                                numbers : {

                                                                color : "#FFFFFF",
                                                                bkgd : "#ff9740",
                                                                rounded : 0.15,
                                                                shadow : {
                                                                x : 0, // x offset (in pixels)
                                                                y : 3, // y offset (in pixels)
                                                                s : 4, // spread
                                                                c : "#cccccc", // color
                                                                a : 0.4	// alpha
                                                                }
                                                                }
                                                                });

                                                            </script></td><td style="padding-left: 10px;"><span class="upclass"><a data-target="#unavailable" data-toggle="modal" href="#">Algebra I</a></span> by <span class="uptutor"><a href="#" data-toggle="modal" data-target="#aurabanks"> Sam Kavolski</a></span>
                                                            <br>
                                                            2 seats available</td>
                                                    </tr>
                                                </tablE><div style="float:left; ">

                                                </div >
                                            </div>
                                        </div>
                                        <div class="panel panel-default panrounded">
                                            <div class="panel-heading tlaunch headrounded">
                                                <span class="widgetTitle"><span class="glyphicon glyphicon-hand-right"></span> Launch Lesson</span>
                                                <a id="launch" class="pull-right" data-placement="top"  rel="popover" data-content="This widget provides a quick link to the launch lesson."><span class="glyphicon glyphicon-info-sign myglyph"></span></a>

                                            </div>
                                            <div class="panel-body" style="padding: 5px;">
                                                <p style="padding:10px; text-align: center;">
                                                    <a href="http://tryme.myetutor.org/alphaSite/appstudent/student.php" class="btn btn-info btn-lg" style="background: #dff0d8; border-color: #57ce94; color: #333;"> Take Lesson</a>&nbsp;&nbsp;
                                                    <a data-target="#pretest" data-toggle="modal" href="#" class="btn btn-default btn-lg" style="background: #fbbcbc; border-color: #ff6160; color: #333;"> Pre-Test</a>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="panel panel-default panrounded">
                                            <div class="panel-heading latest headrounded">
                                                <span class="widgetTitle"><span class="glyphicon glyphicon-th-list"></span> Latest News</span>
                                                <a class="pull-right" id="latestnews" rel="popover" data-placement="bottom" data-content="This widget displays the latest news items from the MyeTutor community.">
                                                    <span class="glyphicon glyphicon-info-sign myglyph"></span></a>

                                            </div>
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-md-9">
                                                        <h4 class="featurette-heading">John Doe has reached the achievement 1000 Hours of Mathematical tutoring.</h4>
                                                    </div>
                                                    <div class="col-md-3 text-center">
                                                        <img src="img/educ_02.png" alt="placeholder" class="featurette-image img-responsive thumbnail newsresized">
                                                    </div>
                                                </div>
                                                <hr>

                                                <div class="row">
                                                    <div class="col-md-9 col-md-push-2">
                                                        <h4 class="featurette-heading">Get back to school sale on credits to help you prepare for the upcoming school year </h4>
                                                    </div>
                                                    <div class="col-md-3 col-md-pull-9 text-center">
                                                        <img src="img/educ_04.png" alt="placeholder" class="featurette-image img-responsive thumbnail newsresized">
                                                    </div>
                                                </div>

                                                <hr>

                                                <div class="row">
                                                    <div class="col-md-9">
                                                        <h4 class="featurette-heading">New classes have been added and will be available from Aug 18th 2014 </h4>
                                                    </div>
                                                    <div class="col-md-3 text-center">
                                                        <img src="img/educ_05.png" alt="placeholder" class="featurette-image img-responsive thumbnail newsresized">
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="row">
                                                    <div class="col-md-9 col-md-push-2">
                                                        <h4 class="featurette-heading">Welcoming Event scheduled for Aug 3rd 2014 for all new accepted tutors </h4>
                                                    </div>
                                                    <div class="col-md-3 col-md-pull-9 text-center">
                                                        <img src="img/educ_01.png" alt="placeholder" class="featurette-image img-responsive thumbnail newsresized">
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="panel panel-default panrounded">
                                            <div class="panel-heading tclasses headrounded">     
                                                <span class="widgetTitle"><span class="glyphicon glyphicon-list-alt"> </span> Today's Classes</span>
                                                <a id="todayclass" class="pull-right" rel="popover" data-placement="top" data-content="This widget displays all available courses for the specific day.">
                                                    <span class="glyphicon glyphicon-info-sign myglyph"></span></a>
                                            </div>
                                            <div class="panel-body" style="padding: 5px;">
                                                <ul class="list-group table-striped">
                                                    <li class="list-group-item ">
                                                        15:30-<a data-target="#unavailable" data-toggle="modal" href="#">Algebra I: Introduction to integers</a> by <a data-target="#unavailable" data-toggle="modal" href="#">S. Kavolski</a><span class="pull-right"><a data-target="#unavailable" data-toggle="modal" href="#">Pre-test</a> | <a data-target="#unavailable" data-toggle="modal" href="#">Material</a></span>
                                                    </li>
                                                    <li class="list-group-item">
                                                        16:30-<a data-target="#unavailable" data-toggle="modal" href="#">Algebra II: Using Integers</a> by <a data-target="#unavailable" data-toggle="modal" href="#">Aura Blantes</a><span class="pull-right"><a data-target="#unavailable" data-toggle="modal" href="#">Pre-test</a> | <a data-target="#unavailable" data-toggle="modal" href="#">Material</a></span>
                                                    </li>
                                                    <li class="list-group-item">
                                                        18:30-<a data-target="#unavailable" data-toggle="modal" href="#">Algebra III: Advanced Integers</a> by <a data-target="#unavailable" data-toggle="modal" href="#">Tom Breier</a><span class="pull-right"><a data-target="#unavailable" data-toggle="modal" href="#">Pre-test</a> | <a data-target="#unavailable" data-toggle="modal" href="#">Material</a></span>
                                                    </li>
                                                    <li class="list-group-item">
                                                        15:30-<a data-target="#unavailable" data-toggle="modal" href="#">Algebra II: Using Integers</a> by <a data-target="#unavailable" data-toggle="modal" href="#">Sam Kavolski</a><span class="pull-right"><a data-target="#unavailable" data-toggle="modal" href="#">Pre-test</a> | <a data-target="#unavailable" data-toggle="modal" href="#">Material</a></span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>

                                    </div>

                                </div><!-- /col-9 -->

                            </div><!-- /padding -->

                        </div>
                        <!-- /main -->

                        <div id="footer" class="foot">
                            <div class="container">
                                <div class="col-md-3 text-left">
                                    <a data-target="#unavailable2" data-toggle="modal" href="http://myetutor.org/" target="_blank" style="color:white;">About us</a>
                                </div>
                                <div class="col-md-3 text-left">
                                    <a data-target="#unavailable2" data-toggle="modal" href="#" style="color:white;">Terms of Use</a>
                                </div>
                                <div class="col-md-3 text-left">
                                    <a data-target="#unavailable2" data-toggle="modal" href="#" style="color:white;">Privacy Policy</a>
                                </div>
                                <div class="col-md-3 text-right">
                                    <a data-target="#unavailable2" data-toggle="modal" href="#" style="color:white;">Copyright 2014</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modals -->
        <div id="aurabanks" class="modal fade" role="dialog">
          <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Tutor Profile</h4>
              </div>
              <div class="modal-body">
                <iframe width="100%" height="600px" src="http://tryme.myetutor.org/tprofile_ext_par.html"></iframe>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              </div>
            </div>

          </div>
        </div>

        <div id="pretest" class="modal fade" role="dialog">
              <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Pretest</h4>
                  </div>
                  <div class="modal-body">
                    <p>Ποιο κλάσμα είναι μεγαλύτερο, το 2/3 ή το 5/7 ;</p>
                    <input value="1" name="question_2B15vdE0k1" type="radio"> Α. το 2<br>
                    <input value="2" name="question_2B15vdE0k1" type="radio"> Β. Το 5<br>
                    <input value="3" name="question_2B15vdE0k1" type="radio"> Γ. δεν ξέρουμε<br><br>
                    <p>Να συμπληρώσεις το ψηφίο που λείπει στον αριθμό 18,…8 αν γνωρίζεις ότι, όταν ο αριθμός στρογγυλοποιείται στο πλησιέστερο δέκατο, γίνεται ίσος με 18,8</p>
                    <input value="1" name="question_oVGWRE0kIB" type="radio"> Α. 0 <br>
                    <input value="2" name="question_oVGWRE0kIB" type="radio"> Β. 1 <br>
                    <input value="3" name="question_oVGWRE0kIB" type="radio"> Γ. 7<br>
                    <input value="4" name="question_oVGWRE0kIB" type="radio"> Δ. 8 <br><br>
                    <p>Ένα κομμάτι κρέας που αγόρασα από το χασάπικο ζύγιζε 2,5 κιλά, και το 1 κιλό ήταν κόκαλο. Πόσο % ήταν το καθαρό κρέας;</p>
                    <input value="1" name="question_5aBhAh7g4G" type="radio"> Α.  66% <br><input value="2" name="question_5aBhAh7g4G" type="radio"> Β. 55% <br>
                    <input value="3" name="question_5aBhAh7g4G" type="radio"> Γ.  60%<br><input value="4" name="question_5aBhAh7g4G" type="radio"> Δ. 40% <br><br>
                    <p>Χρησιμοποιώντας την επιμεριστική ιδιότητα υπολόγισε το γινόμενο : 5*222</p>
                    <input value="1" name="question_htwJwh2Wu9" type="radio"> Α.1000 <br>
                    <input value="2" name="question_htwJwh2Wu9" type="radio"> Β.1110<br><input value="3" name="question_htwJwh2Wu9" type="radio"> Γ.1100<br>
                    <input value="4" name="question_htwJwh2Wu9" type="radio"> Δ.2100<br><br>
                    <p>Ένα βιβλίο κόστιζε 12 ευρώ, και το βιβλιοπωλείο είχε προσφορά τα 3 βιβλία 30 ευρώ. Πόση έκπτωση έκανε;</p>
                    <input value="1" name="question_maq5i5M0g9" type="radio"> Α.  83% <br><input value="2" name="question_maq5i5M0g9" type="radio"> Β. 12% <br>
                    <input value="3" name="question_maq5i5M0g9" type="radio"> Γ.  16,66%<br><input value="4" name="question_maq5i5M0g9" type="radio"> Δ. 6% <br><br>
                    <input name="submit" value="Submit" type="submit">
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  </div>
                </div>

              </div>
            </div>

        <div id="unavailable" class="modal fade" role="dialog">
              <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Under Construction</h4>
                  </div>
                  <div class="modal-body">
                    We are sorry but this function does not work yet.
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  </div>
                </div>

              </div>
            </div>

            <div id="unavailable2" class="modal fade" role="dialog">
              <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Under Construction</h4>
                  </div>
                  <div class="modal-body">
                    We are sorry but the content is not ready yet.
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  </div>
                </div>

              </div>
            </div>
        <!-- script references -->
        <script src='js/lib/moment.min.js'></script>
        <script src='js/lib/jquery-ui.custom.min.js'></script>
        <script src='js/fullcalendar.min.js'></script>
        <script src="js/circles.js"></script>
        <script src="js/bootstrap-datepicker.js"></script>
        <script src="js/Chart.js"></script>
        <script src="js/jquery.dataTables.min.js"></script>


        <script>
               
            circles = [];
            var circle_perc = [83];
            for (var i = 1; i <= 1; i++) {
                var child = document.getElementById('circles-' + i), 
                percentage = 51.42 + (i * 9), circle = Circles.create({
                    id : child.id,
                    value : circle_perc[i - 1],
                    radius : getWidth(),
                    width : 30,
                    colors : ['#e5e5e5', 'rgba(151,187,205,1)'],
                    text : function(value) { return value + '%';
                    }
                });

                circles.push(circle);
            }

            window.onresize = function(e) {
                for (var i = 0; i < circles.length; i++) {
                    circles[i].updateRadius(getWidth());
                }
            };

            function getWidth() {
                return window.innerWidth / 10;
            }
            var radarChartData = {
                labels : ["Algebra", "Geometry", "Calculus", "Combinatorics", "Logic", "Number Theory"],
                datasets : [{
                        label : "My First dataset",
                        fillColor : "rgba(151,187,205,0.2)",
                        strokeColor : "rgba(151,187,205,1)",
                        pointColor : "rgba(151,187,205,1)",
                        pointStrokeColor : "#fff",
                        pointHighlightFill : "#fff",
                        pointHighlightStroke : "rgba(151,187,205,1)",
                        data : [65, 59, 90, 81, 60, 55]
                    }, {
                        label : "My Second dataset",
                        fillColor : "rgba(220,220,220,0.2)",
                        strokeColor : "rgba(220,220,220,1)",
                        pointColor : "rgba(220,220,220,1)",
                        pointStrokeColor : "#fff",
                        pointHighlightFill : "#fff",
                        pointHighlightStroke : "rgba(220,220,220,1)",
                        data : [28, 48, 40, 19, 55, 27]
                    }]
            };
            var randomScalingFactor1 = function(){ return Math.round(Math.random()*100)};

            var barChartData = {
                labels : ["January","February","March","April","May","June","July"],
                datasets : [
                    {
                        fillColor : "rgba(220,220,220,0.5)",
                        strokeColor : "rgba(220,220,220,0.8)",
                        highlightFill: "rgba(220,220,220,0.75)",
                        highlightStroke: "rgba(220,220,220,1)",
                        data : [randomScalingFactor1(),randomScalingFactor1(),randomScalingFactor1(),randomScalingFactor1(),randomScalingFactor1(),randomScalingFactor1(),randomScalingFactor1()]
                    },
                    {
                        fillColor : "rgba(151,187,205,0.5)",
                        strokeColor : "rgba(151,187,205,0.8)",
                        highlightFill : "rgba(151,187,205,0.75)",
                        highlightStroke : "rgba(151,187,205,1)",
                        data : [randomScalingFactor1(),randomScalingFactor1(),randomScalingFactor1(),randomScalingFactor1(),randomScalingFactor1(),randomScalingFactor1(),randomScalingFactor1()]
                    }
                ]

            };
            var randomScalingFactor = function() {
                return Math.round(Math.random() * 100);
            };
            var lineChartData = {
                labels : ["January", "February", "March", "April", "May", "June", "July"],
                datasets : [{
                        label : "My First dataset",
                        fillColor : "rgba(220,220,220,0.2)",
                        strokeColor : "rgba(220,220,220,1)",
                        pointColor : "rgba(220,220,220,1)",
                        pointStrokeColor : "#fff",
                        pointHighlightFill : "#fff",
                        pointHighlightStroke : "rgba(220,220,220,1)",
                        data : [randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor()]
                    }, {
                        label : "My Second dataset",
                        fillColor : "rgba(151,187,205,0.2)",
                        strokeColor : "rgba(151,187,205,1)",
                        pointColor : "rgba(151,187,205,1)",
                        pointStrokeColor : "#fff",
                        pointHighlightFill : "#fff",
                        pointHighlightStroke : "rgba(151,187,205,1)",
                        data : [randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor()]
                    }]

            };

            var randomScalingFactor1 = function() {
                return Math.round(Math.random() * 100);
            };

            var lineChartData = {
                labels : ["January", "February", "March", "April", "May", "June", "July"],
                datasets : [{
                        label : "My First dataset",
                        fillColor : "rgba(220,220,220,0.2)",
                        strokeColor : "rgba(220,220,220,1)",
                        pointColor : "rgba(220,220,220,1)",
                        pointStrokeColor : "#fff",
                        pointHighlightFill : "#fff",
                        pointHighlightStroke : "rgba(220,220,220,1)",
                        data : [randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor()]
                    }, {
                        label : "My Second dataset",
                        fillColor : "rgba(151,187,205,0.2)",
                        strokeColor : "rgba(151,187,205,1)",
                        pointColor : "rgba(151,187,205,1)",
                        pointStrokeColor : "#fff",
                        pointHighlightFill : "#fff",
                        pointHighlightStroke : "rgba(151,187,205,1)",
                        data : [randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor()]
                    }]

            };
        </script>
        <script>
            $(document).ready(function() {
                $('#example2').dataTable();
                var $container = $('#content');
                    
                    

                $('#subject a').click(function(){
                    $('#subject .current').removeClass('current');
                
                    $(this).addClass('current');
                    var subject = $(this).attr('data-filter');
	   
                    $('#subject').attr('data-active', subject);
	 
                    reorder();
	  
                    return false;
                });
	
                $('#topic a').click(function(){
                    $('#topic .current').removeClass('current');
                
                    $(this).addClass('current');
                    var topic = $(this).attr('data-filter');
	   
                    $('#topic').attr('data-active', topic);
	   
                    reorder();
	   
                    return false;
                });
	
                function reorder(){
     
                    var subject = $('#subject').attr('data-active');
                    var topic = $('#topic').attr('data-active');
		 
                    if (typeof subject === 'undefined') {
                        subject = "";
                    }
                    if (typeof topic === 'undefined') {
                        topic = "";
                    }
			 
                    var filterString = subject+topic;
		 
                    if(filterString=="**"){
                        filterString = "*"; 
                    }
			 
                    $container.isotope({
                        filter: filterString,
                        animationOptions: {
                            duration: 750,
                            easing: 'linear',
                            queue: false
                        }
                    });
				 
                }

                $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
                    if ($(e.target).attr('href') == '#mcal') {
                        
                        $("#schedwrapper").show();
                        $("#loading").hide();  
                        $("#results").hide();  

                        var date;
                        var $container2 = $('#content2');
                        $container2.isotope({
                            filter: '*',
                            itemSelector: '.team',
                            animationOptions: {
                                duration: 750,
                                easing: 'linear',
                                queue: false
                            }
                        });
                        $("#calselect").datepicker({
                            autoclose: true,
                            todayHighlight: true,
                            startDate: new Date()
                        })
                        .on("changeDate", function(e){
                            date = e.date.toDateString();
                        })
                        ;
                       
                        $('#calselect .input-group.date').datepicker({
                            autoclose: true,
                            todayHighlight: true
                        });
                        $("#submit").click(function(){
                            $("div.datepicker").hide();
                            $("#schedwrapper").hide();
                            $("#loading").show();
                            $container2.isotope({
                                filter: '*',
                                itemSelector: '.team',
                                animationOptions: {
                                    duration: 750,
                                    easing: 'linear',
                                    queue: false
                                }
                            });
                            
                        
                            sleep(1000, fetch_results);
                            return false;
                        });
                        
                        function fetch_results(){
                          
                            $("div.datepicker").hide();
                            $("#loading").hide();    
                            $("#results").show();
                            $("#tutlessons_available").html(date);
                            $container2.isotope('reLayout');
                        
                    
                    

               
                        };
                        
                        $('#subject2 a').click(function(){
                            $('#subject2 .current').removeClass('current');
                
                            $(this).addClass('current');
                            var subject2 = $(this).attr('data-filter');
       
                            $('#subject2').attr('data-active', subject2);
     
                            reordersched();
      
                            return false;
                        });
    
                        $('#tutors2 a').click(function(){
                            $('#tutors2 .current').removeClass('current');
                
                            $(this).addClass('current');
                            var tutors2 = $(this).attr('data-filter');
       
                            $('#subject2').attr('data-active', tutors2);
     
                            reordersched();
      
                            return false;
                        });
                
                        $('#topic2 a').click(function(){
                            $('#topic2 .current').removeClass('current');
                
                            $(this).addClass('current');
                            var topic2 = $(this).attr('data-filter');
       
                            $('#topic2').attr('data-active', topic2);
       
                            reordersched();
       
                            return false;
                        });
                        
                        function sleep(millis, callback) {
                            setTimeout(function()
                            { callback(); }
                            , millis);
                        };
                        
                        function reordersched(){
     
                            var subject2 = $('#subject2').attr('data-active');
                            var topic2 = $('#topic2').attr('data-active');
                            var tutors2 = $('#tutors2').attr('data-active');
                    
                            if (typeof subject2 === 'undefined') {
                                subject2 = "";
                            }
                            if (typeof topic2 === 'undefined') {
                                topic2 = "";
                            }
                            if (typeof tutors2 === 'undefined') {
                                tutors2 = "";
                            }
                            var filterString2 = subject2+topic2+tutors2;
         
                            if(filterString2=="**"){
                                filterString2 = "*"; 
                            }
             
                            $container2.isotope({
                                filter: filterString2,
                                itemSelector: '.team',
                                animationOptions: {
                                    duration: 750,
                                    easing: 'linear',
                                    queue: false
                                }
                            });
                 
                        }
                        
                    }
                    if ($(e.target).attr('href') == '#reports') {
                        var ctx = document.getElementById("canvas-line").getContext("2d");
                        var ctx1 = document.getElementById("canvasbar").getContext("2d");
                        myBar = new Chart(ctx1).Bar(barChartData, {
                            responsive : true
                        
                        });
                        myLine = new Chart(ctx).Line(lineChartData, {
                            responsive : true
                        });
                        myRadar = new Chart(document.getElementById("canvasrad").getContext("2d")).Radar(radarChartData, {
                            responsive : true
                        });
                    
                    }
                    if ($(e.target).attr('href') == '#resources') {
                        $container.isotope({
                            filter: '*',
                            animationOptions: {
                                duration: 750,
                                easing: 'linear',
                                queue: false
                            }
                        });
                    }
                        
                });
                $('#myTab a:first').tab('show');
                var activeTab = window.location.hash;
                if (activeTab){
                    $('#myTab a[href="#'+activeTab.replace("#",'')+'"]').tab('show');
                    window.location = "#top";
                }
                    
                                   

            });
            function build_calendar() {
                $('#calendar').fullCalendar({
                    header : {
                        left : 'prev,next today',
                        center : 'title',
                        right : 'month,basicWeek,basicDay'
                    },
                    defaultDate : '2015-07-01',
                    editable : true,
                    events : [{
                            title : 'Algebra I by Sam Kavolski | 2 seats available',
                            start : '2015-07-01T12:30:00',
                            end : '2015-07-03T14:30:00',
                            url : '#'

                        }, {
                            title : 'Algebra I, 2 seats available',
                            start : '2015-07-05T12:30:00',
                            end : '2015-07-12T14:30:00',
                            backgroundColor : '#ff6160',
                            borderColor : '#ff6160'

                        }, {
                            title : 'Algebra II, no seats available',
                            start : '2015-07-01T16:30:00',
                            end : '2015-07-01T18:30:00',
                            backgroundColor : '#ff6160',
                            borderColor : '#ff6160'
                        }, {
                            title : 'Introduction to Integers',
                            start : '2015-07-12T12:30:00',
                            end : '2015-07-12T14:30:00',
                            backgroundColor : '#6fafae',
                            borderColor : '#6fafae'
                        }, {
                            title : 'Algebra II, no seats available',
                            start : '2015-07-24T16:30:00',
                            end : '2015-07-24T18:30:00',
                            backgroundColor : '#ff6160',
                            borderColor : '#ff6160'
                        }, {
                            title : 'Geometry, 2 seats available',
                            start : '2015-07-22T12:30:00',
                            end : '2015-07-22T14:30:00'
                        }, {
                            title : 'Introduction to Integers',
                            start : '2015-07-03T12:30:00',
                            end : '2015-07-03T14:30:00',
                            backgroundColor : '#ff9740',
                            borderColor : '#ff9740'
                        }, {
                            title : 'Introduction to Integers by Aura Banks',
                            start : '2015-07-24T12:30:00',
                            end : '2015-07-24T14:30:00',
                            backgroundColor : '#ff9740',
                            borderColor : '#ff9740'
                        }]
                });
            }

            $(function () {
                $('#upcoming').popover();
                $('#launch').popover();
                $('#latestnews').popover();
                $('#todayclass').popover();
                $('#resmat').popover();
                $('#echap').popover();
                $('#tufoll').popover();
                $('#tufav').popover();
                $('#schedule').popover();
            });


        </script>
        <script>
            $( "button" ).click(function() {
                $('.progress-bar').animate(
                {width:'80%'}, 
                {
                    duration:2000,
                    step: function(now, fx) {
                        var data= Math.round(now);
                        $(this).html(data + '%');
            
                    }
                }        
            );
                $('.progress-bar1').animate(
                {width:'70%'}, 
                {
                    duration:2000,
                    step: function(now, fx) {
                        var data= Math.round(now);
                        $(this).html(data + '%');
            
                    }
                }        
            );
                            $('.progress-bar3').animate(
                {width:'68%'}, 
                {
                    duration:2000,
                    step: function(now, fx) {
                        var data= Math.round(now);
                        $(this).html(data + '%');
            
                    }
                }        
            );
                            $('.progress-bar4').animate(
                {width:'92%'}, 
                {
                    duration:2000,
                    step: function(now, fx) {
                        var data= Math.round(now);
                        $(this).html(data + '%');
            
                    }
                }        
            );
                            $('.progress-bar5').animate(
                {width:'88%'}, 
                {
                    duration:2000,
                    step: function(now, fx) {
                        var data= Math.round(now);
                        $(this).html(data + '%');
            
                    }
                }        
            );
                            $('.progress-bar6').animate(
                {width:'100%'}, 
                {
                    duration:2000,
                    step: function(now, fx) {
                        var data= Math.round(now);
                        $(this).html(data + '%');
            
                    }
                }        
            );
            });
        </script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/scripts.js"></script>
    </body>
</html>
