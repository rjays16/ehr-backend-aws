<html>

<head>
    <style>
        html{
            font-family: 'Segoe UI';
            font-weight: 100;
        }

        th.header{
            background-color: darkblue;
            color: floralwhite;
            height: 100;
            border-radius: 15px 15px 0px 0px;
            text-align: left;
        }
        td.label{
            color: gray;
        }
        th.card-header{
            background: gray;
            border-radius: 15px;
        }
        td{
            padding: 3px;
        }
        p.header{
            font-size: 2.5em;
            margin-top: 45px;
            color: darkblue;
        }
        p.title{
            font-size: 2.5em;
            margin-top: 45px;
            color: white;
        }
        p.header2{
            margin-top: -30px;
            color: #2f2f2f;
        }
        div.codeContainer{
            background-color: #ffbd20;
            font-size: 30px;
            color: aliceblue;
        }
        p.codeText{
            padding: 7.5px 30px 12.5px 20px;

        }
    </style>
</head>

<table width='100%'>
    <tr>
        <th class='card-header' colspan="2">
        </th>
    </tr>
    <tr>
        <th colspan='2' class='header'>
            <div>
                <p style='margin: 25px' class='title'>EHRv2</p>
            </div>
        </th>
    </tr>
    <tr>
        <td class='label'>
        </td>
        <td>
            <p class="header">Verify Your Mobile Account!</p>
            <p class='header2'>Welcome to <strong>EHRv2</strong>, {{ $data['name'] }}! Before we get started,
            please input this system generated code to your mobile phone below.</p>
            <div class="codeContainer" style="width: 175px">
                <p class='codeText'> {{ $data['auth_code'] }} </p>
            </div>
        </td>
    </tr>
</table>

</html>
