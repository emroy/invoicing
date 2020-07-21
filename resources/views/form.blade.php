@extends('layout')

@section('title', 'Form Test')

@section('styles')
    @parent
    <style>
        
        div.col-md-6.expanded{
            padding:40px;
        }
        
        div.padding{
            padding-top:20px;
        }
        
        button.delete-btn{
            border:none;
            font-size:2em;
            color:white;
            background-color:transparent;
            cursor:pointer;
            transition:.2s ease-in-out;
        }

        button.delete-btn:hover{
            color:red;
        }

        table.table td{
            vertical-align:middle;
        }

    </style>
@endsection

@section('content')
<div class="row">
    <div class="col-md-6 expanded">
        <h1 style="text-align:center">Invoice Form Test</h1>
        <form id="mainForm">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="clientnameid">Client Name</label>
                    <input type="text" class="form-control" name="clientname" id="clientnameid" data-check="true" data-label="clientname" placeholder="Introduce Client's Name">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="jobdirectionid">Job's Direction</label>
                    <input type="text" class="form-control" name="direction" id="jobdirectionid" data-check="true" data-label="direction" placeholder="Job's Direction">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="postcodeid">Post Code</label>
                    <input type="text" class="form-control" name="postcode" id="postcodeid" data-check="true" data-label="clientname" placeholder="Introduce Post Code">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="phoneid">Phone Number</label>
                    <input type="text" class="form-control" name="phone" id="phoneid" data-check="true" data-label="direction" placeholder="+44(0000) 000 000">
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="invoicedateid">Invoice Date</label>
            <input type="date" class="form-control" name="date" id="invoicedateid" data-check="true" data-label="date" placeholder="Introduce Client's Name">
        </div>
        <div class="form-group">
            <label for="emailid">Email address</label>
            <input type="email" class="form-control" name="email" id="emailid" data-check="true" data-label="email" aria-describedby="emailHelp" placeholder="Enter email">
            <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
        </div>
        <!--div class="form-group form-check">
            <input type="checkbox" class="form-check-input" id="exampleCheck1">
            <label class="form-check-label" for="exampleCheck1">Check me out</label>
        </div-->
        <button class="btn btn-primary" data-submit="true">Submit</button>
    </form>
    </div>
    <div class="col-md-6 expanded">
        <h2>Invoice Items</h2>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="itemname">New Item</label>
                    <input type="text" class="form-control" data-table="true" data-label="itemname" id="itemname" placeholder="Introduce Item">
                </div>
                <div class="form-group">
                    <label for="itemprice">Item Price</label>
                    <input type="number" class="form-control" data-table="true" data-label="itemprice" id="itemprice" placeholder="Price (£)">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="itemquantity">Item Quantity</label>
                    <input type="number" class="form-control" data-table="true" data-label="itemquantity" id="itemquantity" value="1">
                </div>
            </div>
        </div>
        <button class="btn btn-warning" id="addnew">Add new Item</button>
        <div class="padding">
            <table class="table table-dark">
                <thead>
                    <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">Price</th>
                    <th scope="col">Delete</th>
                    </tr>
                </thead>
                <tbody id="tbody" quantity="0">
                    
                </tbody>
                <tfooter>
                    @foreach($var as $value)
                        <tr>
                            <th scope="col"></th>
                            <th scope="col"></th>
                            <th scope="col">{{ $value->description }}</th>
                            <th scope="col" data-var="{{ $value->val }}" id="{{ $value->description }}"> {{ $value->val }}%</th>
                            <th scope="col"></th>
                        </tr>    
                    @endforeach
                    <tr>
                        <th scope="col"></th>
                        <th scope="col"></th>
                        <th scope="col">Sub Total</th>
                        <th scope="col" id="subtotal">0.00 £</th>
                        <th scope="col"></th>
                    </tr>
                    <tr>
                        <th scope="col"></th>
                        <th scope="col"></th>
                        <th scope="col">Total</th>
                        <th scope="col" id="total">0.00 £</th>
                        <th scope="col"></th>
                    </tr>
                </tfooter>
            </table>
        </div>
    </div>
</div>

@endsection

@section('scripts')
@parent
<script>

function calculation(){
    var subtotal = $("#subtotal");
    var total = $("#total");
    var elements = $("#tbody").children(); //tr's
    var res = 0;
    var des = 0;
    $.each(elements, (_, item) => {
        res += $($(item).children()[2]).text() * $($(item).children()[3]).data('v');
    })

    subtotal.text(parseInt(res).toFixed(2) + " £");

    $('th[data-var]').each(function(_, item){
        des += $(this).data('var') * res / 100;
    });

    total.text(parseInt(res + des).toFixed(2) + " £");
}

function deleteRow(el){
    var element = $(el).data('del');
    $(`[data-item='${element}']`).remove();
    resetCounter();
    calculation();
}

function resetCounter() {
    var tbody = $('#tbody');
    var rows = tbody.children()
    var fields = rows.children(':not([data-v])');
    var counter = 1;
    fields.each(function(_, item){
        $(item).text(counter)
        tbody.attr('quantity', counter);
        counter++
    })
}


$("#addnew").click(function(){
    var data = $('[data-table="true"]');
    var obj = {}
    data.each(function(_, item){
        obj[$(item).data('label')] = $(item).val()
    });

    var tbody = $('#tbody');
    var quantity = parseInt(tbody.attr('quantity')) + 1;

    var newrow = $(`<tr data-item="item${quantity}"><td>${quantity}</td><td data-n="itemname" data-v="${obj.itemname}">${obj.itemname}</td><td data-n="itemquantity" data-v="${obj.itemquantity}">${obj.itemquantity}</td><td data-n="itemprice" data-v="${parseFloat(obj.itemprice).toFixed(2)}">£ ${parseFloat(obj.itemprice).toFixed(2)}</td><td data-v><button data-del="item${quantity}" onclick="deleteRow(this)" class="delete-btn">&times;</button></td></tr>`);

    tbody.append(newrow);
    tbody.attr('quantity', quantity);

    calculation();

});

$("[data-submit]").click(function(event){
    event.preventDefault();
    var mainData = $("#mainForm").serialize();
    var rows = $("#tbody").children();

    table = [];
    rows.each(function(_,item){
        var ob = {}
        $(item).children().each(function(_,i){
            ob[$(i).data('n')] = String($(i).data('v'))
        });
        table.push(ob)

    })

    var string = JSON.stringify({info: mainData, table:table});

    $.ajax({
        'type':'post',
        'url':'api/submit/invoice',
        data: { data: string},
        success: (response) => {
            console.log(response)   
        }
    });


});


</script>
@endsection