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
<section id="main-content">
          <section class="wrapper">
              <!-- page start-->
              <div class="row">
                  <div class="col-lg-12">
                      <section class="panel">
                          <header class="panel-heading">
                              Reports
                          </header>
                          <div class="panel-body">
                              <section id="unseen">
                                <table class="table table-bordered table-striped table-condensed">
                                  <thead>
                                  <tr>
                                        <th>Date#</th>
                                        <th>Order Number</th>
                                        <th>Programma Nome
                                        </th>
                                        <th>Price
                                        </th>
                                        <th>Commission
                                        </th>
                                        <th>Programma Prepayment Status
                                        </th>
                                        <th>Time of visit
                                        </th>
                                        <th>Evento Nome
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
                                                <td>{$item->amount} €
                                                </td>
                                                <td>{$item->commission} €
                                                </td>
                                                <td>{$item->status}
                                                </td>
                                                <td>{$item->time_of_visit}
                                                </td>
                                                </td>
                                                <td>{$item->evento_name}
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
                                        <td colspan="5" align="center">
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