<?php
session_start(); 

function array_search_partial($arr, $keyword) {
    $result = [];
    foreach($arr as $string) {
        if (strpos($string, $keyword) !== FALSE)
            $result[] = $string;
        }
        print_r($string);exit;
        return $result;   
}
$name='';
$url_search='';
$client_search ='';
$query = '';
$connect = new PDO("mysql:host=localhost; dbname=test", "root", "");

if(isset($_POST['submit'])){
    //print_r($_POST);
    $name=$_POST['tag_search'];
    $url_search=$_POST['url_search'];
    $client_search=$_POST['client_search'];
    if(!empty($_POST['tag_search'])):
        $tags_arr = explode(',',$_POST['tag_search']);
    else:
        $tags_arr = [];
    endif;
    $query.= "
    SELECT * FROM projects WHERE status!=''    
    ";
    if(!empty($tags_arr) && count($tags_arr) > 0):
        foreach($tags_arr as $key => $val):
            if($val != ''):
                    $query.= ' AND ';
                $query.= "FIND_IN_SET('".trim($val)."', tag)";
            endif;
        endforeach;
    endif;
    if(!empty($url_search)):
        if(!empty($client_search) || (!empty($tags_arr) && count($tags_arr) > 0)):
            $query.= " AND link like '%".$url_search."%'";
        else:
            $query.= "AND link like '%".$url_search."%'";
        endif;
        
    endif;
    if(!empty($client_search)):
        if(!empty($url_search) || (!empty($tags_arr) && count($tags_arr) > 0)):
            $query.= " AND client like '%".$client_search."%'";
        else:
            $query.= "AND client like '%".$client_search."%'";
        endif;
        
    endif;

    $statement = $connect->prepare($query);

    $statement->execute();

    $rows = [];
    while($row = $statement->fetch(PDO::FETCH_ASSOC))
    {
        // /print_r($row);
        $rows[] = $row; 
    }
    
}else{
        $query.= "
        SELECT * FROM projects";
        $statement = $connect->prepare($query);

        $statement->execute();
        $rows = [];
        while($row = $statement->fetch(PDO::FETCH_ASSOC))
        {
            // /print_r($row);
            $rows[] = $row; 
        }
}
    //get feture project
    $query= "
        SELECT * FROM projects WHERE is_feature>=1 ";
        $statement = $connect->prepare($query);

        $statement->execute();
        $feature_data = [];
        while($row = $statement->fetch(PDO::FETCH_ASSOC))
        {
            // /print_r($row);
            $feature_data[] = $row; 
        }


?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Projects</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tokenfield/0.12.0/css/bootstrap-tokenfield.min.css">
  

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">

  <!-- =======================================================
  * Template Name: NiceAdmin
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Updated: Apr 20 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body class="toggle-sidebar">

  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
      <a href="textauto.php" class="logo d-flex align-items-center">
        <img src="assets/img/logo.png" alt="">
        <span class="d-none d-lg-block">Admin</span>
      </a>
      <!-- <i class="bi bi-list toggle-sidebar-btn"></i> -->
    </div><!-- End Logo -->


    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">

        <li class="nav-item dropdown pe-3">

          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            <span class="d-none d-md-block dropdown-toggle ps-2"><?php echo $_SESSION['user_name']; ?></span>
          </a><!-- End Profile Iamge Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li>
              <a class="dropdown-item d-flex align-items-center" href="logout.php">
                <i class="bi bi-box-arrow-right"></i>
                <span>Sign Out</span>
              </a>
            </li>

          </ul><!-- End Profile Dropdown Items -->
        </li><!-- End Profile Nav -->

      </ul>
    </nav><!-- End Icons Navigation -->

  </header><!-- End Header -->

  <!-- ======= Sidebar ======= -->
  <!-- <aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

      <li class="nav-item">
        <a class="nav-link collapsed" href="textauto.php">
          <i class="bi bi-grid"></i>
          <span>Dashboard</span>
        </a>
      </li>


      <li class="nav-item">
        <a class="nav-link collapsed" href="logout.php">
          <i class="bi bi-box-arrow-in-right"></i>
          <span>Logout</span>
        </a>
      </li>


    </ul>

  </aside> -->
  <!-- End Sidebar-->

  <main id="main" class="main">

    <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Projects</h5>
              <form class="row g-3" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <div class="col-md-4">
                    <label>Select Tag</label>
                    <div class="input-group">
                        <input type="text" name="tag_search" id="search_data" placeholder="" autocomplete="off" class="form-control input-lg" value="<?php echo $name; ?>"/>
                    </div>
                    <br />
                    <span id="country_name"></span>
                </div>
                <div class="col-md-4">
                    <label>Select Client</label>
                    <div class="input-group">
                        <input type="text" name="client_search" id="client_search" placeholder="" autocomplete="off" class="form-control input-lg" value="<?php echo $client_search; ?>"/>
                    </div>
                    <br />
                    <span id="country_name"></span>
                </div>
                <div class="col-md-4">
                    <label>Link</label>
                    <div class="input-group">
                        <input type="text" name="url_search" id="url_search" placeholder="" autocomplete="off" class="form-control input-lg"/ value="<?php echo $url_search; ?>">
                    </div>
                </div>
                <div class="col-12">
                  <button class="btn btn-primary" type="submit" name="submit">Search</button>
                  <button type="button" id="copy-table-button" class="btn btn-primary" data-clipboard-target="#datatable">  Copy </button>
                  <button type="button" id="clear-button" class="btn btn-primary">  Clear </button>
                </div>
              </form>
              
            </div>
          </div>

        </div>

      </div>
    </section>
    <?php 
    if (isset($_SESSION['id']) && isset($_SESSION['user_name'])) { ?>
        <section class="section">
        <div class="row">
            <div class="col-lg-12">

            <div class="card">
                <div class="card-body">
                <!-- Table with stripped rows -->
                <table class="table datatable" id="example">
                    <thead>
                    <tr>
                            <th>Project</th>
                            <th style="display:none">Url</th>
                            <th>Tags</th>
                            <th>Industry</th>
                            <th>Theme</th>
                            <th>Plugins</th>
                            <th>Payment Gateways</th>
                            <th>Other Comments</th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($rows) && count($rows) > 0){
                                foreach($rows as $key => $val){ ?>
                                    <tr>
                                        <td scope="row"><a href="<?php echo $val['link']; ?>" target="_blank"><?php echo $val['name']; ?></a> (<a href="statuschange.php?id=<?php echo $val['id']; ?>&status=<?php echo $val['status']; ?>"><?php echo $val['status']; ?>)</a><br>
                                        <?php echo $val['client']; ?></td>
                                        <td style="display:none" class="<?php if($val['status'] == 'active'){ echo  'list'; }?>"><a href="<?php echo $val['link']; ?>" target="_blank"><?php echo $val['link']; ?></a></td>
                                        <td><?php echo preg_replace('/(?<!\ )[,]/', '$0 ', $val['tag']); ?></td>
                                        <td><?php echo $val['industry']; ?></td>
                                        <td><?php echo $val['theme']; ?></td>
                                        <td><?php echo $val['plugins']; ?></td>
                                        <td><?php echo $val['payment_gateways']; ?></td>
                                        <td><div class="comment_content content_<?php echo $key; ?>" data-id="<?php echo $key; ?>" data-text="<?php echo $val['other_comments']; ?>"><p><?php echo $val['other_comments']; ?></p> </div></td>
                                    </tr>
                                <?php } ?>
                            <?php } else { ?>
                                <tr>
                                    <td colspan="10" align="center">No data found</td>
                                </tr>
                            <?php } ?>
                            <tr>
                                <td>
                                    <h5 class="card-title">Featured Projects</h5>
                                </td> 
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <?php if(!empty($feature_data) && count($feature_data) > 0){
                            foreach($feature_data as $key => $val){ 
                              $skey = 's'.$key; ?>
                                <tr>
                                    <td scope="row"><a href="<?php echo $val['link']; ?>" target="_blank"><?php echo $val['name']; ?></a> (<a href="statuschange.php?id=<?php echo $val['id']; ?>&status=<?php echo $val['status']; ?>"><?php echo $val['status']; ?>)</a><br>
                                    <?php echo $val['client']; ?></td>
                                    <td style="display:none" class="<?php if($val['status'] == 'active'){ echo  'list'; }?>"><a href="<?php echo $val['link']; ?>" target="_blank"><?php echo $val['link']; ?></a></td>
                                    <td><?php echo preg_replace('/(?<!\ )[,]/', '$0 ', $val['tag']); ?></td>
                                    <td><?php echo $val['industry']; ?></td>
                                    <td><?php echo $val['theme']; ?></td>
                                    <td><?php echo $val['plugins']; ?></td>
                                    <td><?php echo $val['payment_gateways']; ?></td>
                                    <td><div class="comment_content content_<?php echo $skey; ?>" data-id="<?php echo $skey; ?>" data-text="<?php echo $val['other_comments']; ?>"><p><?php echo $val['other_comments']; ?></p> </div></td>
                                </tr>
                            <?php } ?>
                            <?php } else { ?>
                                <tr>
                                    <td colspan="10" align="center">No data found</td>
                                </tr>
                            <?php } ?>

                    </tbody>
                </table>
                <!-- End Table with stripped rows -->

                </div>
            </div>

            </div>
        </div>
        </section>
        
    <?php } else {?>
      <?php echo "<script>location.href='user_login.php';</script>"; 

  } ?>
  </main><!-- End #main -->


  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/chart.js/chart.umd.js"></script>
  <script src="assets/vendor/echarts/echarts.min.js"></script>
  <script src="assets/vendor/quill/quill.js"></script>
  <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tokenfield/0.12.0/bootstrap-tokenfield.js"></script>
  <script>
  $(document).ready(function(){
      
    $('#search_data').tokenfield({
        autocomplete :{
            source: function(request, response)
            {
                
                jQuery.get('fetch.php', {
                    query : request.term,
                }, function(data){
                    data = JSON.parse(data);
                    response(data);
                });
            },
            delay: 100
        }
    });

    $('#search_data').on('tokenfield:createtoken', function (event) {
      var existingTokens = $(this).tokenfield('getTokens');
      $.each(existingTokens, function(index, token) {
          if (token.value === event.attrs.value)
              event.preventDefault();
      });
    });

    $('#client_search').tokenfield({
        autocomplete :{
            source: function(request, response)
            {
                $('#client_search').parent().find('.token').remove();
                jQuery.get('client_search.php', {
                    query : request.term,
                }, function(data){
                    data = JSON.parse(data);
                    response(data);
                });
            },
            delay: 100
        }
    });

    
    $('#client_search').on('tokenfield:createtoken', function (event) {
      $('#client_search').parent().find('.token').remove();
      var existingTokens = $(this).tokenfield('getTokens');
      $.each(existingTokens, function(index, token) {
          if (token.value === event.attrs.value)
              event.preventDefault();
      });
    });

    // $('#search').click(function(){
    //     $('#country_name').text($('#search_data').val());
    // });


    $( ".comment_content" ).each(function( index ) {
      var id = $(this).attr('data-id');
      length = 10;
    cHtml = $(".content_"+id).html();
    cText = $(".content_"+id).text().substr(0, length).trim();
    $(".content_"+id).addClass("compressed").html(cText + "... <a href='#' class='exp' data-id="+id+">More</a>");
    // window.handler = function()
    // {
    //     $('.exp').click(function(){
    //       var ids = $(this).attr('data-id');
    //       cHtmls = $(".content_"+ids).attr('data-text');
    //       cTexts = $(".content_"+ids).attr('data-text').substr(0, length).trim();
    //         if ($(".content_"+ids).hasClass("compressed"))
    //         {
    //             $(".content_"+ids).html(cHtmls + "<a href='#' class='exp' data-id="+ids+">Less</a>");
    //             $(".content_"+ids).removeClass("compressed");
    //             handler();
    //             return false;
    //         }
    //         else
    //         {
    //             $(".content_"+ids).html(cTexts + "... <a href='#' class='exp' data-id="+ids+">More</a>");
    //             $(".content_"+ids).addClass("compressed");
    //             handler();
    //             return false;
    //         }
    //     });
    // }
    // handler();
});

  $(document).on('click','.exp',function(){
            var ids = $(this).attr('data-id');
            cHtmls = $(".content_"+ids).attr('data-text');
            cTexts = $(".content_"+ids).attr('data-text').substr(0, length).trim();
              if ($(".content_"+ids).hasClass("compressed"))
              {
                  $(".content_"+ids).html(cHtmls + "<a href='#' class='exp' data-id="+ids+">Less</a>");
                  $(".content_"+ids).removeClass("compressed");
                  
                  return false;
              }
              else
              {
                  $(".content_"+ids).html(cTexts + "... <a href='#' class='exp' data-id="+ids+">More</a>");
                  $(".content_"+ids).addClass("compressed");
                  
                  return false;
              }
          });

    });

  const copyButton = document.querySelector('#copy-table-button');

  const copyToClipboard = (_) => {
    const dataElements = document.querySelectorAll('tr > td:first-child + td.list');
    const data = Array.from(dataElements).map(element => element.textContent).join('\n');
    const blob = new Blob([data], {type: 'text/plain'});
    const clipboardItem = new ClipboardItem({'text/plain': blob});

    navigator.clipboard.write([clipboardItem]);
  }

  copyButton.addEventListener('click', copyToClipboard);


  $(document).on('click','#clear-button',function(){
    location.replace('textauto.php');
  });
  
  
</script>

</body>

</html>