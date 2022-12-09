<div class="alert alert-info">
    <i class="icon fas fa-info"></i><b> Note </b>:
    using websocket functionality allows live chat and reduce server load.
  </div>

<div id="nodeServerMessageViewr" class="">
</div>

<div class="row" id="nodeServerBodyViewr">
    @if ($is_node_on->value == '1')
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <div class="callout callout-success">
                <p>The Node server is Up and Running!</p>
            </div>
        </div>
        <div class="col-md-3"></div>
        <div class="col-md-2"></div>
        <div class="col-md-8">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Mode</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody id="tbodyNode">

                </tbody>
            </table>
            <button onclick="nodeStopServer(this)" class="btn btn-danger float-right">Stop Server</button>
        </div>
        <div class="col-md-2"></div>
    @else
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <div class="callout callout-danger">
                <p>The Node server is currently Down!</p>
            </div>
            <button onclick="nodeStartServer(this)" class="btn btn-success float-right">Start Server</button>
        </div>
        <div class="col-md-3"></div>
    @endif
</div>
