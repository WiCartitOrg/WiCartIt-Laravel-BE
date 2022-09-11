<!DOCTYPE html>
<html>
    <head>
        <style>
            .w3-myfont {
              font-family: "Comic Sans MS" ;
            }
        
            .w3-custom-purple {
              background: #6a41ed;
            }
            .w3-green {
                background: #008000
            }

            .w3-text-white {
                color: white
            }

            .w3-text-yellow{
                color: yellow
            }
            
            .w3-center{
                justify-content: center
            }
        </style>
    </head>

    <body class="w3-myfont w3-custom-purple w3-text-white">
        <div class="w3-center">
        <br/><br/>
        <p>
            <strong> Hello from this side <b>{{$buyer_request->buyer_first_name}} {{$buyer_request->buyer_last_name}}</b>,</strong>
        </p>

        <b><hr/></b>

        <p>
            <b> 
                <strong>
                    Welcome to Wicart! Your shopping and buying experience is about to be re-defined!  <br/>
                    Feel free to visit us often for irresistible product and promotional offerings..<br/>
                    we look forward to hearing from you!
                </strong>
                <br/>
                <!--<strong><hr/></strong>-->
                <br/>
                <i class="w3-text-yellow">Please open your verification mail to activate your Wicart account</i>
            </b>
        </p>

        <b><hr/></b>

        <p>
           <b> Cheers! </b>
        </p>
        <br/><br/>
        </div>
    </body>
</html>