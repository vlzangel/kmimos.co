<?php

	$message .= '
		<style>

			p{
			    text-align: justify;
			}

	        li *{
			    font-family: Roboto;
	        }

	        li a {
	            text-decoration: none;
	            text-align: center;
	            color: #444444;
				display: block;
				overflow: hidden;
	        }

	        li {
			    min-width: 180px;
			    margin: 5px;
			    overflow: hidden;
		        margin: 5px 0px 15px;
	            position: relative;
	        }

	        li > div{
	        	overflow: hidden;
	        }
	        
	        li img{
			    float: left;
		        margin: 0px 10px 0px 0px;
	        }

	        li p{
        	    text-align: right;
	        }

	        li div a{
			    padding: 10px;
			    background: #00d2b7;
			    color: #000000;
			    font-size: 15px;
			    font-weight: bold;
			    margin: 10px 0px 10px;
			    width: 200px;
			    float: right;
	        }

	        @media screen and (max-width: 550px) {
	            li div img {
				    float: none;
				    margin: 0px auto;
				    display: block;
				    width: 100%;
				    box-sizing: border-box;
				    padding: 5px;
	            }
	            li div div {
					padding: 5px;
					font-size: 13px;
					float: none;
					width: 100%;
					box-sizing: border-box;
	            }
	            li div div a {
					position: relative;
					width: 100%;
					box-sizing: border-box;
				    bottom: initial;
    				right: initial;
				}
            	li div div p {
            		padding: 5px;
            	}
	        }

	    </style>
    ';

?>