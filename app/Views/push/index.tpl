<script type="text/javascript">
    function answers()
    {
        var selectedanswer=document.getElementById("recordlimit").value;
        var frm = document.getElementById("frm");
        frm.action = "index.php?controller=push&action=index&limit="+selectedanswer;
        frm.submit();
    }
</script>
<form id="frm" action="index.php?controller=push&action=index" method="post">
    <section id="main-content">
        <section class="wrapper">
            <!-- page start-->
            <div class="row">
                <div class="col-lg-12">
                    <section class="panel">
                        <header class="panel-heading">
                            <div class="row">
                                <div class="col-xs-6 col-sm-6 placeholder">
                                    <h3 align="left"><span> Push Notifications</span></h3>
                                </div>
                                <div class=" col-xs-6 col-sm-4">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="search" name="search" value="{if isset($search)}{$search}{/if}" placeholder="Search name...">
                                          <span class="input-group-btn">
                                           <input class="btn btn-default" type="submit" id="go" name="go" value="Go!" />
                                          </span>
                                    </div>

                                </div>

                                <div class=" col-xs-6 col-sm-2">
                                    <a class="btn btn-primary" href="index.php?controller=push&action=send">send a push</a>

                                </div>

                            </div>
                        </header>


                        <div class="panel-body">
                            <section id="unseen">
                                <table class="table table-bordered table-striped table-condensed">
                                    <thead>
                                    <tr>
                                        <td align="center">Status </td>
                                        <td>Target</td>
                                        <td>Name </td>
                                        <td>Time </td>
                                        <td>Action</td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    {if isset($message)}
                                        {foreach $message as $key=>$item}
                                            <tr>
                                                <td align="center">
                                                <input type="checkbox" {if $item->status==1}checked="checked"{/if} />
                                                </td>
                                                <td>{$item->target}</td>
                                                <td>{$item->name}</td>
                                                <td>{$item->time|date_format:"%Y-%m-%d %H:%M:%S"}</td>
                                                <td><a href="index.php?controller=push&action=resend&id={$item->id}" class="btn btn-success">resend</a>
                                                    <span>|</span>
                                                    <a href="index.php?controller=push&action=update&id={$item->id}" class="btn btn-info">Edit</a>
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
                                        <td colspan="2" align="center">
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