<script type="text/javascript">
    function answers()
    {
        var selectedanswer=document.getElementById("recordlimit").value;
        var frm = document.getElementById("frm");
        frm.action = "index.php?controller=product&action=index&limit="+selectedanswer;
        frm.submit();
    }
</script>
<form id="frm" action="index.php?controller=product&action=index" method="post">

    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">

        <div class="row">
            <div class="col-xs-6 col-sm-12">
                <ol class="breadcrumb">
                    <li><a href="#">Home</a></li>
                    <li class="active">Product</li>
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-6 col-sm-6 placeholder">
                <h1 class="page-header" align="left"><span>  Products</span></h1>
            </div>
            <div class="col-sm-2"></div>

            <div class="col-sm-4">
                <div class="input-group">
                    <input type="text" class="form-control" id="search" name="search" value="{if isset($search)}{$search}{/if}" placeholder="Search for...">
                  <span class="input-group-btn">
                    <input class="btn btn-default" type="submit" id="go" name="go" value="Go!" />
                  </span>
                </div>
            </div>

        </div>

        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>ImageUrl </th>
                    <th>Categories</th>
                    <th>FeedId</th>
                    <th>ProductUrl </th>
                    <th>ProgramName </th>
                    <th>Price </th>
                    <th>SourceProductId </th>
                    <th>Id </th>
                    <th>GroupingId</th>

                </tr>
                </thead>
                <tbody>
                {if isset($products)}
                    {foreach $products as $key=>$item}
                        <tr>
                            <td>{$item['name']}</td>
                            <td>{$item['description']}</td>
                            <td><img src="{$item['productImage']['url']}" width="150px" height="150px"/>
                            </td>
                            <td>{foreach $item['categories'] as $key=>$cate}
                                <span>{$cate['name']}</span>
                                {/foreach}
                            </td>
                            {foreach $item['offers'] as $key=>$off}
                                <td>{$off['feedId']}
                                </td>
                                <td>{$off['productUrl']}
                                </td>
                                <td>{$off['programName']}
                                </td>
                                <td>{foreach $off['priceHistory'] as $key=>$price}
                                    <span>{$price['price']['value']}</span><span> {$price['price']['currency']}</span>
                                    {/foreach}
                                </td>
                                <td>{$off['sourceProductId']}
                                </td>
                                </td>
                                <td>{$off['id']}
                                </td>
                            {/foreach}



                        </tr>
                    {/foreach}
                {/if}

                </tbody>
                <tr>
                    <td colspan="5" align="right">

                        <ul class="pagination" align="center">

                            {if isset($listPage)}

                                <li>{$listPage}</li>
                            {/if}
                        </ul>
                    </td>
                    <td colspan="9" align="center">
                        <div>
                            Page Size:
                            <select id="recordlimit" onchange="answers();">
                                <option {if isset($limit) && $limit==10}selected="selected"{/if} value="10">10 </option>
                                <option {if isset($limit) && $limit==20}selected="selected"{/if} value="20">20 </option>
                                <option {if isset($limit) && $limit==50}selected="selected"{/if} value="50">50 </option>
                                <option {if isset($limit) && $limit==100}selected="selected"{/if} value="100">100 </option>
                                <option {if isset($limit) && $limit==$totalrecords}selected="selected"{/if} value="{$totalrecords}">All</option>
                            </select>
                            Total Record:<input type="text" size="2" value="{$totalrecords}" disabled="disabaled" />
                            Total Page:<input type="text" size="2" value="{$totalpages}" disabled="disabaled"/>
                        </div>

                    </td>
                </tr>
            </table>
        </div>

    </div>
</form>