<script type="text/javascript">
    function answers()
    {
        var selectedanswer=document.getElementById("recordlimit").value;
        var frm = document.getElementById("frm");
        frm.action = "index.php?controller=systemuser&action=index&limit="+selectedanswer;
        frm.submit();
    }
</script>
<form id="frm" action="index.php?controller=systemuser&action=index" method="post">
    <section id="main-content">
        <section class="wrapper">
            <!-- page start-->
            <div class="row">
                <div class="col-lg-12">
                    <section class="panel">
                        <header class="panel-heading">
                            Users
                        </header>
                        <div class="panel-body">
                            <div style="float:right;padding-bottom:20px;padding-right:10px;"><button type="button" onclick="window.location='index.php?controller=systemuser&action=create'" class="btn btn-success btn-xs"><i class="fa fa-plus"></i> Create </button></div>
                            <div style="clear:both;"></div>
                            <section id="unseen">
                                <table class="table table-bordered table-striped table-condensed">
                                    <thead>
                                    <tr>
                                        <th width="10%">User Name</th>
                                        <th width="5%">Status</th>
                                        <th width="20%">Email</th>
                                        <th width="15%">Full Name</th>
                                        <th width="10%">Account Name</th>
                                        <th>Action </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    {if isset($users)}
                                        {foreach $users as $key=>$item}
                                            <tr>
                                                <td>{$item->username}</td>
                                                <td>
                                                    {if $item->status && $item->status==1}
                                                        Actived
                                                    {else}
                                                        InActived
                                                    {/if}
                                                </td>
                                                <td>{$item->email}</td>
                                                <td>{$item->fullname}</td>
                                                <td>{$item->account_name}</td>

                                                <td>
                                                    <button type="button" onclick="window.location='index.php?controller=systemuser&action=view&id={$item->id}'" class="btn btn-success btn-xs"><i class="fa fa-eye"></i> View </button>
                                                    {if $item->status && $item->status==1}
                                                        <button type="button" onclick="window.location='index.php?controller=systemuser&action=active&status=0&id={$item->id}'" class="btn btn-warning btn-xs"><i class="fa fa-lock"></i> InActive </button>
                                                    {else}
                                                        <button type="button" onclick="window.location='index.php?controller=systemuser&action=active&status=1&id={$item->id}'" class="btn btn-warning btn-xs"><i class="fa fa-unlock"></i> Active </button>
                                                    {/if}

                                                    <button type="button" onclick="window.location='index.php?controller=systemuser&action=edit&id={$item->id}'" class="btn btn-info btn-xs"><i class="fa fa-edit"></i> Edit </button>
                                                    <button type="button" onclick="window.location='index.php?controller=systemuser&action=delete&id={$item->id}'" class="btn btn-danger btn-xs"><i class="fa fa-power-off"></i> Delete </button>
                                                    <button type="button" onclick="window.location='index.php?controller=systemuser&action=changepassword&id={$item->id}'" class="btn btn-primary btn-xs"><i class="fa fa-key"></i> Change Password </button>

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