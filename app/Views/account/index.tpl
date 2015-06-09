<script type="text/javascript">
    function answers()
    {
        var selectedanswer=document.getElementById("recordlimit").value;
        var frm = document.getElementById("frm");
        frm.action = "index.php?controller=account&action=index&limit="+selectedanswer;
        frm.submit();
    }
</script>
<form id="frm" action="index.php?controller=product&action=index" method="post">
    <section id="main-content">
        <section class="wrapper">
            <!-- page start-->
            <div class="row">
                <div class="col-lg-12">
                    <section class="panel">
                        <header class="panel-heading">
                            Accounts
                        </header>
                        <div class="panel-body">
                            <div style="float:right;padding-bottom:20px;padding-right:10px;"><button type="button" onclick="window.location='index.php?controller=account&action=create'" class="btn btn-success btn-xs"><i class="fa fa-plus"></i> Create </button></div>
                            <div style="clear:both;"></div>
                            <section id="unseen">
                                <table class="table table-bordered table-striped table-condensed">
                                    <thead>
                                    <tr>
                                        <th width="20%">Name</th>
                                        <th width="10%">Type</th>
                                        <th width="5%">Status</th>
                                        <th width="10%">Users</th>
                                        <th width="15%">Created Date</th>
                                        <th width="15%">Modified Date </th>
                                        <th>Action </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    {if isset($accounts)}
                                        {foreach $accounts as $key=>$item}
                                            <tr>
                                                <td>{$item->name}</td>
                                                <td>{$item->type}</td>
                                                <td>
                                                    {if $item->status && $item->status==1}
                                                        Actived
                                                    {else}
                                                        InActived
                                                    {/if}
                                                </td>
                                                <td>
                                                    <button type="button" onclick="window.location='index.php?controller=systemuser&action=index&account_id={$item->id}'" class="btn btn-success btn-xs"><i class="fa fa-eye"></i> View </button>
                                                </td>
                                                <td>{$item->created_day|date_format:"%Y-%m-%d %H:%M:%S"}
                                                </td>
                                                <td>
                                                    {$item->modified_day|date_format:"%Y-%m-%d %H:%M:%S"}
                                                </td>
                                                <td>
                                                    <button type="button" onclick="window.location='index.php?controller=account&action=view&id={$item->id}'" class="btn btn-success btn-xs"><i class="fa fa-eye"></i> View </button>
                                                    {if $item->status && $item->status==1}
                                                        <button type="button" onclick="window.location='index.php?controller=account&action=active&status=0&id={$item->id}'" class="btn btn-warning btn-xs"><i class="fa fa-lock"></i> InActive </button>
                                                    {else}
                                                        <button type="button" onclick="window.location='index.php?controller=account&action=active&status=1&id={$item->id}'" class="btn btn-warning btn-xs"><i class="fa fa-unlock"></i> Active </button>
                                                    {/if}

                                                    <button type="button" onclick="window.location='index.php?controller=account&action=edit&id={$item->id}'" class="btn btn-info btn-xs"><i class="fa fa-edit"></i> Edit </button>
                                                    <button type="button" onclick="window.location='index.php?controller=account&action=delete&id={$item->id}'" class="btn btn-danger btn-xs"><i class="fa fa-power-off"></i> Delete </button>
                                                </td>
                                            </tr>
                                        {/foreach}
                                    {/if}

                                    </tbody>
                                    <tr>
                                        <td colspan="3" align="right">

                                            <ul class="pagination" align="center">

                                                {if isset($listPage)}
                                                    <li>{$listPage}</li>
                                                {/if}
                                            </ul>
                                        </td>
                                        <td colspan="4" align="center">
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
                            </section>
                        </div>
                    </section>
                </div>
            </div>

            <!-- page end-->
        </section>
    </section>
</form>