<?php
  
  function getProductYearTable($kunde, $current_page, $per_page){
      global $mspdo;
      if(!isset($current_page) || $current_page == 0){
  		$surrent_page = 1;
      }
      
      $start = ($current_page - 1) * $per_page;
      /* funktion aus der productsModule.php */
      $total = getAllProducts($kunde)[0]['ID'];
      $pages = ceil($total/$per_page);
      if($pages == 0){
        $pages = 1;
      }
      
      $this_year = date("Y");
  
      $sql = "SELECT * FROM products P
      LEFT JOIN Kunden K ON P.kundenId = K.ID
      LEFT JOIN Category C ON C.ID = P.categoryId
      WHERE YEAR(P.createDate) = '".$this_year."' AND k.Name LIKE '".$kunde."%' ORDER BY ID ASC OFFSET $start ROWS FETCH NEXT $per_page ROWS ONLY;
      ";
    /* funktion aud der dbconnect.php */
      $results = db_get_FETCH_ASSOC($mspdo, $sql);

      /* funktion aus der productsModule.php */
      $configPage = getConfig_Page($kunde);
      $products_Column = $configPage->products;
      //print_r($products_Column);
    
      $paging = '';
      $paging .= '<!-- Pagination with icons -->';
      $paging .= '<div class="row">';
          $paging .= '<div class="col-md-6"></div><!-- End 1. col-md-6 -->';
          $paging .= '<div class="col-md-6">';
          $paging .= '<div class="row">';
              $paging .= '<div class="col-md-6"></div><!-- End 1. col-md-6 -->';
              $paging .= '<div class="col-md-6">';
              $paging .= '<div class="pagination-container-one">';
                  $paging .= '<nav aria-label="Page navigation example">';
                  $paging .= '<ul class="pagination justify-content-end" id="yPaginate">';
                      if($page > 1){
                      $paging .= '<li class="page-item" id="'.($page-1).'">';
                          $paging .= '<a class="page-link" aria-label="Previous">';
                              $paging .= '<span aria-hidden="true">&laquo;</span>';
                          $paging .= '</a>';
                      $paging .= '</li>';
                      }else{
                      $paging .= '<li class="page-item disabled">';
                          $paging .= '<a class="page-link" aria-label="Previous">';
                              $paging .= '<span aria-hidden="true">&laquo;</span>';
                          $paging .= '</a>';
                      $paging .= '</li>';
                      }
  
                      $paging .= '<li class="page-item page-active">';
                          $paging .= '<a class="page-link">'.$page.'</a>';
                      $paging .= '</li>';
  
                      if($page < $pages){
                          $paging .= '<li class="page-item" id="'.($page+1).'">';
                              $paging .= '<a class="page-link" aria-label="Next">';
                                  $paging .= '<span aria-hidden="true">&raquo;</span>';
                              $paging .= '</a>';
                          $paging .= '</li>';
                      }else{
                      $paging .= '<li class="page-item disabled">';
                              $paging .= '<a class="page-link" aria-label="Next">';
                                  $paging .= '<span aria-hidden="true">&raquo;</span>';
                              $paging .= '</a>';
                          $paging .= '</li>';
                      }
                  $paging .= '</ul>';
                  $paging .= '</nav>';
              $paging .= '</div><!-- End 1. pagination-container-->';
              $paging .= '<div class="pagination-container-two">';
                  $paging .= '<nav aria-label="Page navigation example">';
                  $paging .= '<ul class="pagination justify-content-center" id="yPaginateTwo">';
                      $paging .= '<li class="page-item">';
                      $paging .= '<span class="y-page-link">'.$page.' von '.$pages.'</span>';
                      $paging .= '</li>';
                  $paging .= '</ul>';
                  $paging .= '<input type="hidden" name="yTotalPages" id="TotalPages" value="'.$pages.'" />';
                  $paging .= '</nav>';
              $paging .= '</div><!-- End 2. pagination-container-->';
              $paging .= '</div><!-- End 3. col-md-6 -->';
          $paging .= '</div><!-- End 2. row -->';
          $paging .= '</div><!-- End 2. col-md-6 -->';
      $paging .= '</div><!-- End 1. Row -->';
      $paging .= '<!-- End Pagination with icons -->';
      $table = '';
      $table .= $paging; 
      if(!empty($results)){
        //print_r($results);
          $table .= '<div class="table-responsive" id="pContent">';
              $table .= '<form action="products-paging.php" id="yPaging" name="yPaging" method="POST">';
                $table .= '<input type="hidden" name="func_yPaging" id="func_yPaging" value="getProductsYearPaging" />';
                $table .= '<input type="hidden" name="ycurrent_page" id="ycurrent_page" value="'.$current_page.'" />';
                $table .= '<input type="hidden" name="yper_page" id="yper_page" value="'.$per_page.'" />';
                $table .= '<input type="hidden" name="ykunde" id="ykunde" value="'.$kunde.'" />';
              $table .= '</form>';
              $table .= '<table class="table table-striped">';
                  $table .='<thead>';
                  $table .= '<tr class="text-start">';
                  for($i=0;$i<count($product_Column);$i++){
                    $table .= '<th scope="col">'.$product_Column[$i].'</th>';
                  }
                $table .= '</tr>';
                      $table .= '</tr>';
                  $table .= '</thead>';
                  $table .= '<tbody>';
                  foreach($results as $product){
                    $table .= '<tr class="text-start">';
                    for($i=0;$i<count($product_Column);$i++){
                      $column = $product_Column[$i];
                      $value = isset($product[$column]) ? $product[$column] : '---';
                      $table .= '<td>'.$value.'</td>';
                    }
                  $table .= '</tr>';
                  }
                  $table .= '</tbody>';
              $table .= '</table>';
          $table .= '</div>';
          $pagingFooter = '';
          $pagingFooter .= '<!-- Pagination with icons -->';
          $pagingFooter .= '<div class="row">';
            $pagingFooter .= '<div class="col-md-6"></div><!-- End 1. col-md-6 in 1. Row -->';
            $pagingFooter .= '<div class="col-md-6">';
              $pagingFooter .= '<div class="row">';
                $pagingFooter .= '<div class="col-md-6"></div><!-- End 1. col-md-6  in 2. Row -->';
                $pagingFooter .= '<div class="col-md-6">';
                  $pagingFooter .= '<div class="pagination-container-one">';
                    $pagingFooter .= '<nav aria-label="Page navigation example">';
                      $pagingFooter .= '<ul class="pagination justify-content-end" id="yPaginateFoot">';
                        if($page > 1){
                          $pagingFooter .= '<li class="page-item" id="'.($page-1).'">';
                            $pagingFooter .= '<a class="page-link" aria-label="Previous">';
                              $pagingFooter .= '<span aria-hidden="true">&laquo;</span>';
                            $pagingFooter .= '</a>';
                          $pagingFooter .= '</li>';
                        }else{
                          $pagingFooter .= '<li class="page-item disabled">';
                            $pagingFooter .= '<a class="page-link" aria-label="Previous">';
                              $pagingFooter .= '<span aria-hidden="true">&laquo;</span>';
                            $pagingFooter .= '</a>';
                          $pagingFooter .= '</li>';
                        }
  
                        $pagingFooter .= '<li class="page-item page-active">';
                          $pagingFooter .= '<a class="page-link">'.$page.'</a>';
                        $pagingFooter .= '</li>';
  
                        if($page < $pages){
                          $pagingFooter .= '<li class="page-item" id="'.($page+1).'">';
                            $pagingFooter .= '<a class="page-link" aria-label="Next">';
                              $pagingFooter .= '<span aria-hidden="true">&raquo;</span>';
                            $pagingFooter .= '</a>';
                          $pagingFooter .= '</li>';
                        }else{
                          $pagingFooter .= '<li class="page-item disabled">';
                            $pagingFooter .= '<a class="page-link" aria-label="Next">';
                              $pagingFooter .= '<span aria-hidden="true">&raquo;</span>';
                            $pagingFooter .= '</a>';
                          $pagingFooter .= '</li>';
                        }
                      $pagingFooter .= '</ul>';
                    $pagingFooter .= '</nav>';
                  $pagingFooter .= '</div><!-- End 1. pagination-container-one-->';
                  $pagingFooter .= '<div class="pagination-container-two">';
                    $pagingFooter .= '<nav aria-label="Page navigation example">';
                      $pagingFooter .= '<ul class="pagination justify-content-center" id="yPaginateTwoFoot">';
                        $pagingFooter .= '<li class="page-item">';
                          $pagingFooter .= '<span class="y-page-link">'.$page.' von '.$pages.'</span>';
                        $pagingFooter .= '</li>';
                      $pagingFooter .= '</ul>';
                    $pagingFooter .= '</nav>';
                  $pagingFooter .= '</div><!-- End 2. pagination-container-two-->';
                $pagingFooter .= '</div><!-- End 2. col-md-6  in 2. Row -->';
              $pagingFooter .= '</div><!-- End 2. row -->';
            $pagingFooter .= '</div><!-- End 2. col-md-6 in 1. Row -->';
          $pagingFooter .= '</div><!-- End 1. Row -->';
          $pagingFooter .= '<!-- End Pagination with icons -->';
          
          $table .= $pagingFooter;
      }else{
          $table .= '<div class="table-responsive">';
              $table .= '<span class="text-start">Es gibt keine products</span>';
          $table .= '</div>';
      }
      return $table;
  }
?>
