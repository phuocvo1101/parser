<script type="text/javascript">
    function answers()
    {
        var selectedanswer=document.getElementById("recordlimit").value;
        var frm = document.getElementById("frm");
        frm.action = "index.php?controller=report&action=index&limit="+selectedanswer;
        frm.submit();
    }
</script>
<form id="frm" action="index.php?controller=report&action=index" method="post">

    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">

        <div class="row">
            <div class="col-xs-6 col-sm-12">
                <ol class="breadcrumb">
                    <li><a href="#">Home</a></li>
                    <li class="active">Report</li>
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-6 col-sm-6 placeholder">
                <h1 class="page-header" align="left"><i class="fa fa-shopping-cart"></i><span>  Reports</span></h1>
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
                    <th>Date#</th>
                    <th>Order Number</th>
                    <th>Programma Nome
                    </th>
                    <th>Merchant</th>
                    <th>Price
                    </th>
                    <th>Commission
                    </th>
                    <th>Programma Prepayment Status
                    </th>
                    <th>Time of visit
                    </th>
                    <th>Time In session
                    </th>
                    <th>Time Last modified
                    </th>
                    <th>Evento Nome
                    </th>
                    <th>Reason
                    </th>
                    <th>Sito Nome
                    </th>
                    <th>Elem Grafico Nome
                    </th>


                </tr>
                </thead>
                <tbody>
                {if isset($reports)}
                    {foreach $reports as $key=>$item}
                        <tr>
                            <td>{$item->date}</td>
                            <td>{$item->unique_id_ordernumber}</td>
                            <td>{$item->programma_name}
                            </td>
                            <td>{$item->merchantId}</td>
                            <td>{$item->amount} €
                            </td>
                            <td>{$item->commission} €
                            </td>
                            <td>{$item->status}
                            </td>
                            <td>{$item->time_of_visit}
                            </td>
                            <td>{$item->time_in_session}
                            </td>
                            </td>
                            <td>{$item->time_last_modified}
                            </td>
                            <td>{$item->evento_name}
                            </td>
                            <td>{$item->reason}
                            </td>
                            <td>{$item->site_name}
                            </td>
                            <td>{$item->elem_grafico_name}
                            </td>

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