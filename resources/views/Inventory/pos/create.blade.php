@extends('inc.full_master')
@section('head')

<title>POS</title>
<style>
    .category-section{
        background: #fff;
        padding: 5px;
    }
    .cus-btn{
        border:1px solid #864fe0;
        color:#864fe0;
        line-height: 28.8px;
        font-weight: 700;
        font-size: 1.125rem;
        text-align: center;
        padding-top: .75rem;
        padding-bottom: .75rem;
        padding-left: .5rem;
        padding-right: .5rem;
        background-color: rgb(255 255 255 / 1);
        border-radius: .25rem;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        cursor: pointer;
        margin-bottom: .5rem;
        margin-right:5px;
        margin-left: 5px;
        width: 50%;
    }
    .cus-btn.active{
        background: #864fe0;
        color:#fff;
    }
    .cat-brand-list{
        max-height: 73vh;
        overflow-y: scroll;
    }
    .cat-box{
        border: 1px solid #864fe0;
        padding: 10px;
        width: 49%;
        cursor: pointer;
    }
    .cat-box:hover{
        background: #864fe0;
        color:#fff;
    }

    /* products */
    .product-section{
        gap: .5rem;
        flex-direction: column;
        display: flex;
        grid-column: span 1 / span 1;
    }
    @media (min-width: 640px) {
        .product-section{
            grid-column: span 2 / span 2;
        }
    }
    .section-title{
        background: #fff;
        padding-top: .75rem;
        padding-bottom: .75rem;
        padding-left: .5rem;
        padding-right: .5rem;
        text-align: center;
        font-size: 1.125rem;
        font-weight: 700;
        line-height: 28.8px;
        color:#864fe0;
    }
    .search-container{
        gap: .5rem;
        display: flex;
        position: relative;
    }
    .search-input{
        outline: 2px solid transparent;
        outline-offset: 2px;
        padding-left: 2.75rem;
        padding-top: .875rem;
        padding-bottom: .875rem;
        padding-right: 1rem;
        background-color: rgb(255 255 255 / 1);
        border-style: none;
        width: 100%;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        border-color: #6b7280;
        border-width: 1px;
        border-radius: 0;
        font-size: 1rem;
        line-height: 1.5rem;
        --tw-shadow: 0 0 #0000;
        margin:0;
    }
    .search-input:focus{
        border:2px solid #2563eb;
    }
    .btn-search{
        padding-left: 1rem;
        padding-right: 1rem;
        background-color:#fff;
        cursor: pointer;
        text-transform: none;
        font-family: inherit;
        font-feature-settings: inherit;
        font-variation-settings: inherit;
        font-size: 100%;
        font-weight: inherit;
        line-height: inherit;
        letter-spacing: inherit;
        color: inherit;
        margin: 0;
        padding: 0 1rem;
    }
    .btn-search img{
        width: 1.5rem;
        height: 1.5rem;
        max-width: 100%;
        display: block;
        vertical-align: middle;
    }
    .search-list{
        --tw-shadow: 0 10px 15px -3px rgb(0 0 0 / .1), 0 4px 6px -4px rgb(0 0 0 / .1);
        --tw-shadow-colored: 0 10px 15px -3px var(--tw-shadow-color), 0 4px 6px -4px var(--tw-shadow-color);
        box-shadow: var(--tw-ring-offset-shadow, 0 0 #0000), var(--tw-ring-shadow, 0 0 #0000), var(--tw-shadow);
        padding: .75rem;
        background-color:#fff;
        --tw-border-opacity: 1;
        border-color: rgb(226 232 240 / 1);
        border-width: 1px;
        overflow-y: auto;
        gap: .5rem;
        flex-direction: column;
        width: 100%;
        max-height: 24rem;
        display: flex;
        z-index: 10;
        top: 4rem;
        position: absolute;
    }
    .search-list-item{
        padding: .5rem;
        --tw-bg-opacity: 1;
        background-color: rgb(248 250 252 /1);
        --tw-border-opacity: 1;
        border-color: rgb(226 232 240 / var(--tw-border-opacity));
        border-width: 1px;
        border-radius: .25rem;
        cursor: pointer;
    }
    .search-list-item:hover{
        --tw-bg-opacity: 1;
        background-color: rgb(226 232 240 / 1);
    }
    .products-container{
        overflow-y: scroll;
    }
    @media (min-width: 768px) {
        .products-container{
            max-height: 77vh;
        }
    }
    .product-list{
        gap: .5rem;
        grid-template-columns: repeat(1, minmax(0, 1fr));
        display: grid;
    }
    @media (min-width: 640px) {
        .product-list{
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }
    @media (min-width: 1024px) {
        .product-list{
            grid-template-columns: repeat(2,minmax(0,1fr));
        }
    }
    @media (min-width: 1280px) {
        .product-list{
            grid-template-columns: repeat(3,minmax(0,1fr));
        }
    }
    .product-box{
        padding: .5rem;
        --tw-bg-opacity: 1;
        background-color: rgb(255 255 255 / var(--tw-bg-opacity));
        --tw-border-opacity: 1;
        border-color: rgb(248 250 252 / var(--tw-border-opacity));
        border-width: 2px;
        gap: .25rem;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        display: flex;
        position: relative;
    }
    .product-img{
        overflow: hidden;
        align-items: center;
        display: flex;
    }
    .product-img img{
        border-radius: 7px;
        max-height: 9rem;
        max-width: 100%;
        height: auto;
        display: block;
        vertical-align: middle;
    }
    .product-content{
        padding-top: .25rem;
        gap: .125rem;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        width: 100%;
        display: flex;
    }
    .product-name{
        --tw-text-opacity: 1;
        color: rgb(22 78 99 / var(--tw-text-opacity));
        line-height: 1.25;
        font-weight: 700;
        font-size: .75rem;
        text-align: center;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        width: 100%;
    }
    .product-code{
        --tw-text-opacity: 1;
        color: rgb(100 116 139 / var(--tw-text-opacity));
        line-height: .75rem;
        font-weight: 400;
        font-size: 10px;
    }
    .product-stock{
        --tw-text-opacity: 1;
        color: rgb(22 78 99 / var(--tw-text-opacity));
        line-height: 14.4px;
        font-weight: 500;
        font-size: .75rem;
    }
    .product-hover{
        background: #70809082;
        transition-property: color, background-color, border-color, text-decoration-color, fill, stroke, opacity, box-shadow, transform, filter, -webkit-backdrop-filter;
        transition-property: color, background-color, border-color, text-decoration-color, fill, stroke, opacity, box-shadow, transform, filter, backdrop-filter;
        transition-property: color, background-color, border-color, text-decoration-color, fill, stroke, opacity, box-shadow, transform, filter, backdrop-filter, -webkit-backdrop-filter;
        transition-timing-function: cubic-bezier(.4,0,.2,1);
        transition-duration: .15s;
        justify-content: center;
        align-items: center;
        width: 100%;
        height: 100%;
        display: none;
        position: absolute;
    }
    .product-box:hover .product-hover{
        display: flex;
    }
    .stock-msg{
        --tw-text-opacity: 1;
        color: rgb(220 38 38 / var(--tw-text-opacity));
        font-size: .875rem;
        line-height: 1.25rem;
        padding-top: .25rem;
        padding-bottom: .25rem;
        padding-left: .5rem;
        padding-right: .5rem;
        --tw-bg-opacity: 1;
        background-color: rgb(226 232 240 / var(--tw-bg-opacity));
        border-radius: 9999px;
    }
    .add-cart-box{
        gap: 1rem;
        justify-content: center;
        align-items: center;
        flex-grow: 1;
        display: flex;

    }
    .cart-btn{
        --tw-bg-opacity: 1;
        background-color: rgb(255 255 255 /1);
        border-radius: 5px;
        justify-content: center;
        align-items: center;
        width: 2rem;
        height: 2rem;
        display: flex;
        cursor: pointer;
        font-family: inherit;
        font-feature-settings: inherit;
        font-variation-settings: inherit;
        font-size: 100%;
        font-weight: inherit;
        line-height: inherit;
        letter-spacing: inherit;
        color: inherit;
        margin: 0;
        padding: 0;
    }
    .cart-btn img{
        width: 1rem;
        height: 1rem;
        max-width: 100%;
        display: block;
        vertical-align: middle;
    }
    .cart-qty{
        --tw-text-opacity: 1;
        color: rgb(255 255 255 / var(--tw-text-opacity));
        letter-spacing: -.025em;
        line-height: 1.375;
        font-weight: 600;
        font-size: 1.125rem;
    }
    .customer-box-container{
        position: relative;
    }
    .customer-inner-container{
        gap: .5rem;
        display: flex;
        margin-bottom: .5rem;
    }
    .customer-input-container{
        flex-grow: 1;
        display: flex;
        position: relative;
    }
    .customer-search-input-img{
        transform: translate(0, -50%) rotate(0) skew(0) skewY(0) scaleX(1) scaleY(1);
        width: 1.5rem;
        height: 1.5rem;
        top: 50%;
        left: .75rem;
        position: absolute;
        max-width: 100%;
        display: block;
        vertical-align: middle;
    }
    .customer-search-input{
        outline: 2px solid transparent;
        outline-offset: 2px;
        padding-left: 2.75rem;
        padding-top: .875rem;
        padding-bottom: .875rem;
        padding-right: 1rem;
        --tw-bg-opacity: 1;
        background-color: rgb(255 255 255 / var(--tw-bg-opacity));
        border-style: none;
        width: 100%;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        border-color: #6b7280;
        border-width: 1px;
        border-radius: 0;
        font-size: 1rem;
        line-height: 1.5rem;
        --tw-shadow: 0 0 #0000;
    }
    .customer-search-input:focus{
        --tw-ring-offset-shadow: 0 0 0 0 #fff;
        --tw-ring-shadow: 0 0 0 calc(2px + 0px) #2563eb;
        box-shadow: var(--tw-ring-offset-shadow), var(--tw-ring-shadow), 0 0 #0000;

    }
    .customer-add-btn{
        padding-left: 1rem;
        padding-right: 1rem;
        --tw-bg-opacity: 1;
        background-color: rgb(255 255 255 / 1);
        cursor: pointer;
    }
    .customer-add-btn img{
        width: 1.5rem;
        height: 1.5rem;
        max-width: 100%;
        display: block;
        vertical-align: middle;
    }
    .customer-search-list{
        --tw-shadow: 0 10px 15px -3px rgb(0 0 0 / .1), 0 4px 6px -4px rgb(0 0 0 / .1);
        --tw-shadow-colored: 0 10px 15px -3px var(--tw-shadow-color), 0 4px 6px -4px var(--tw-shadow-color);
        box-shadow: var(--tw-ring-offset-shadow, 0 0 #0000), var(--tw-ring-shadow, 0 0 #0000), var(--tw-shadow);
        padding: .75rem;
        background-color: rgb(255 255 255 /1);
        border-color: rgb(226 232 240 / 1);
        border-width: 1px;
        overflow-y: auto;
        gap: .5rem;
        flex-direction: column;
        width: 100%;
        max-height: 24rem;
        display: flex;
        z-index: 10;
        position: absolute;
    }
    .customer-search-list-item{
        padding: .5rem;
        background-color: rgb(248 250 252 /1);
        border-color: rgb(226 232 240 / 1);
        border-width: 1px;
        border-radius: .25rem;
        cursor: pointer;
    }
    .customer-search-list-item:hover{
        background-color: rgb(226 232 240 / 1);
    }
    .product-cart-container{
        overflow-y: scroll;
        overflow-x: auto;
        margin-top: .5rem;
    }
    @media (min-width: 1024px) {
        .product-cart-container{
            max-height: 64vh;
            min-height: 300px;
        }
    }
    .table-container{
        table-layout: auto;
        width: 100%;
        text-indent: 0;
        border-color: inherit;
        border-collapse: collapse;
    }
    .table-header{
        background: #fbf9f9;
        top: 0;
        position: sticky;
    }
    .table-header .talbe-header-th{
        color:#864fe0;
        font-weight: 700;
        font-size: 1rem;
        line-height: 1.5rem;
        padding: .5rem;
    }
    .product-price-modal{
        overflow-y: auto;
        z-index: 10;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        position: fixed;
    }
    .product-price-modal-container{
        text-align: center;
        padding-top: 1rem;
        padding-bottom: 5rem;
        padding-left: 1rem;
        padding-right: 1rem;
        justify-content: center;
        align-items: flex-end;
        min-height: 100vh;
        display: flex;
    }
    @media (min-width: 640px) {
        .product-price-modal-container{
            padding: 0;
            display: block;
        }
    }
    .product-price-modal-shadow{
        transition-property: opacity;
        transition-timing-function: cubic-bezier(.4,0,.2,1);
        transition-duration: .15s;
        --tw-bg-opacity: .75;
        background-color: rgb(107 114 128 / var(--tw-bg-opacity));
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        position: fixed;
    }
    .product-price-modal-vertical{
        display: none;
    }
    @media (min-width: 640px) {
        .product-price-modal-vertical{
            vertical-align: middle;
            height: 100vh;
            display: inline-block;
        }
    }
    .product-price-modal-content{
        transition-property: all;
        transition-timing-function: cubic-bezier(.4,0,.2,1);
        transition-duration: .15s;
        --tw-shadow: 0 20px 25px -5px rgb(0 0 0 / .1), 0 8px 10px -6px rgb(0 0 0 / .1);
        --tw-shadow-colored: 0 20px 25px -5px var(--tw-shadow-color), 0 8px 10px -6px var(--tw-shadow-color);
        box-shadow: var(--tw-ring-offset-shadow, 0 0 #0000), var(--tw-ring-shadow, 0 0 #0000), var(--tw-shadow);
        vertical-align: bottom;
        text-align: left;
        background-color: rgb(255 255 255 /1);
        border-radius: .5rem;
        overflow: hidden;
        transform: translate(0, 0) rotate(0) skew(0) skewY(0) scaleX(1) scaleY(1);
        display: inline-block;
    }
    @media (min-width: 640px) {
        .product-price-modal-content{
            vertical-align: middle;
            max-width: 32rem;
            width: 100%;
            margin-top: 2rem;
            margin-bottom: 2rem;
        }

    }
    .product-price-modal-body{
        padding-top: 1.25rem;
        padding-bottom: 1rem;
        padding-left: 1rem;
        padding-right: 1rem;
        background-color: rgb(255 255 255 /1);
    }
    @media (min-width: 640px) {
        .product-price-modal-body{
            padding: 1.5rem;
            padding-bottom: 1rem;
        }
    }
    .modal-input{
        color: rgb(148 163 184 /1);
        background-color: rgb(248 250 252 /1);
        border-color: rgb(226 232 240 /1);
        border-radius: .5rem;
        width: 100%;
        margin-top: .5rem;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        border-width: 1px;
        padding: .5rem .75rem;
        font-size: 1rem;
        line-height: 1.5rem;
        --tw-shadow: 0 0 #0000;
        border-style: solid;
    }
    .modal-input:focus{
        outline: 2px solid transparent;
        outline-offset: 2px;
        --tw-ring-inset: var(--tw-empty);
        --tw-ring-offset-width: 0px;
        --tw-ring-offset-color: #fff;
        border-color: transparent;
        --tw-ring-opacity: 1;
        --tw-ring-color: rgb(203 213 225 / var(--tw-ring-opacity));
        --tw-ring-offset-shadow: var(--tw-ring-inset) 0 0 0 var(--tw-ring-offset-width) var(--tw-ring-offset-color);
        --tw-ring-shadow: var(--tw-ring-inset) 0 0 0 calc(1px + var(--tw-ring-offset-width)) var(--tw-ring-color);
        box-shadow: var(--tw-ring-offset-shadow), var(--tw-ring-shadow), var(--tw-shadow, 0 0 #0000);
    }
    :disabled {
        cursor: default;
    }
    .product-price-modal-footer{
        padding-top: .75rem;
        padding-bottom: .75rem;
        padding-left: 1rem;
        padding-right: 1rem;
        background-color: rgb(249 250 251 / 1);
    }
    @media (min-width: 640px) {
        .product-price-modal-footer{
            padding-left: 1.5rem;
            padding-right: 1.5rem;
            flex-direction: row-reverse;
            display: flex;
        }
    }
    .price-modal-btn.update{
        --tw-shadow: 0 1px 2px 0 rgb(0 0 0 / .05);
        --tw-shadow-colored: 0 1px 2px 0 var(--tw-shadow-color);
        box-shadow: var(--tw-ring-offset-shadow, 0 0 #0000), var(--tw-ring-shadow, 0 0 #0000), var(--tw-shadow);
        font-weight: 500;
        font-size: 1rem;
        line-height: 1.5rem;
        padding-top: .5rem;
        padding-bottom: .5rem;
        padding-left: 1rem;
        padding-right: 1rem;
        color: rgb(255 255 255 /1);
        background-color: rgb(220 38 38 /1);
        border-color: transparent;
        border-width: 1px;
        border-radius: .375rem;
        justify-content: center;
        width: 100%;
        display: inline-flex;
        cursor: pointer;
    }
    .price-modal-btn.update:hover{
        background-color: rgb(185 28 28 /1);
    }
    .price-modal-btn.update:focus{
        --tw-ring-offset-width: 2px;
        --tw-ring-opacity: 1;
        --tw-ring-color: rgb(239 68 68 /1);
        --tw-ring-offset-shadow: var(--tw-ring-inset) 0 0 0 var(--tw-ring-offset-width) var(--tw-ring-offset-color);
        --tw-ring-shadow: var(--tw-ring-inset) 0 0 0 calc(2px + var(--tw-ring-offset-width)) var(--tw-ring-color);
        box-shadow: var(--tw-ring-offset-shadow), var(--tw-ring-shadow), var(--tw-shadow, 0 0 #0000);
        outline: 2px solid transparent;
        outline-offset: 2px;
    }
    .price-modal-btn.cancel{
        --tw-shadow: 0 1px 2px 0 rgb(0 0 0 / .05);
        --tw-shadow-colored: 0 1px 2px 0 var(--tw-shadow-color);
        box-shadow: var(--tw-ring-offset-shadow, 0 0 #0000), var(--tw-ring-shadow, 0 0 #0000), var(--tw-shadow);
        color: rgb(55 65 81 /1);
        font-weight: 500;
        font-size: 1rem;
        line-height: 1.5rem;
        padding-top: .5rem;
        padding-bottom: .5rem;
        padding-left: 1rem;
        padding-right: 1rem;
        background-color: rgb(255 255 255 /1);
        border-color: rgb(209 213 219 /1);
        border-width: 1px;
        border-radius: .375rem;
        justify-content: center;
        width: 100%;
        display: inline-flex;
        margin-top: .75rem;
        cursor: pointer;
    }
    .price-modal-btn.cancel:hover{
        background-color: rgb(249 250 251 /1);
    }
    .price-modal-btn.cancel:focus{
        --tw-ring-offset-width: 2px;
        --tw-ring-opacity: 1;
        --tw-ring-color: rgb(99 102 241 / var(--tw-ring-opacity));
        --tw-ring-offset-shadow: var(--tw-ring-inset) 0 0 0 var(--tw-ring-offset-width) var(--tw-ring-offset-color);
        --tw-ring-shadow: var(--tw-ring-inset) 0 0 0 calc(2px + var(--tw-ring-offset-width)) var(--tw-ring-color);
        box-shadow: var(--tw-ring-offset-shadow), var(--tw-ring-shadow), var(--tw-shadow, 0 0 #0000);
        outline: 2px solid transparent;
        outline-offset: 2px;
    }
    @media (min-width: 640px) {
        .price-modal-btn{
            font-size: .875rem;
            line-height: 1.25rem;
            width: auto;
            margin-left: .75rem;
        }
        .price-modal-btn.cancel{
            margin-top: 0;
        }
    }
    .checkout-cart-btn{
        background: #864fe0;
        border-radius: .125rem;
        justify-content: center;
        align-items: center;
        width: 1rem;
        height: 1rem;
        display: flex;
        cursor: pointer;
    }
    .checkout-cart-btn img{
        width: 1rem;
        height: 1rem;

    }
    .checkout-cart-qty{
        color: rgb(22 78 99 /1);
        letter-spacing: -.025em;
        font-weight: 400;
        font-size: 1rem;
        line-height: 1.5rem;
    }
    .checkout-cart-qty-input{
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        border: 0;
        height: 30px;
        line-height: 30px;
        outline: 0;
        text-align: center;
        width: 45px;
    }
    .remove-checkout-cart{
        padding: 0;
        background-color: rgb(248 113 113 / 1);
        border-radius: 9999px;
        justify-content: center;
        align-items: center;
        cursor: pointer;
        width: 20px;
        height: 20px;
        display: flex;
        margin: auto;
    }
    .remove-checkout-cart img{
        width: 20px;
        height: 20px;
        max-width: 100%;
        display: block;
        vertical-align: middle;
    }
    .table-footer{
        background-color: rgb(255 255 255 /1);
        bottom: 0;
        position: sticky;
        display: table-footer-group;
    }
    .table-footer::before {
        content: '';
        position: absolute;
        z-index: -1;
        width: 100%;
        height: 100%;
        background: #f0f0f0;
    }
    .checkout-cart-total-text{
        border-color: #fbf9f9;
        background: #fbf9f9;
        color: rgb(22 78 99 /1);
        font-weight: 500;
        font-size: 1rem;
        line-height: 1.5rem;
        padding: .5rem;
        border-bottom-width: 1px;
        height: 3rem;
    }
    .checkout-cart-total-amount{
        border-color: #fbf9f9;
        background: #fbf9f9;
        color: rgb(22 78 99 /1);
        font-weight: 500;
        font-size: 1rem;
        line-height: 1.5rem;
        text-align: right;
        padding: .5rem;
        border-bottom-width: 1px;
        width: 6rem;
        height: 3rem;
    }
    .checkout-cart-total-del{
        border-color: #fbf9f9;
        background: #fbf9f9;
        padding: .5rem;
        border-bottom-width: 1px;
        width: 3rem;
        height: 3rem;
    }
    .coupon-input{
        background: #fbf9f9;
        outline: 2px solid transparent;
        outline-offset: 2px;
        color: rgb(107 114 128 /1);
        font-weight: 400;
        font-size: 1rem;
        line-height: 1.5rem;
        padding-right: .25rem;
        padding-left: .5rem;
        padding-top: .25rem;
        padding-bottom: .25rem;
        border-color: rgb(203 213 225 /1);
        border-radius: 5px;
        width: 198.88px;
        height: 2rem;
        border-width: 1px;
        border-style: solid;
    }
    .coupon-apply{
        background-color: rgb(34 197 94 /1);
        border-radius: 3px;
        justify-content: center;
        align-items: center;
        --tw-translate-y: -50%;
        transform: translate(0, -50%) rotate(0) skew(0) skewY(0) scaleX(1) scaleY(1);
        width: 1.5rem;
        height: 1.5rem;
        display: flex;
        top: 50%;
        right: .25rem;
        position: absolute;
        cursor: pointer;
    }
    .coupon-remove{
        background-color: rgb(254 226 226 /1);
        border-radius: 3px;
        justify-content: center;
        align-items: center;
        --tw-translate-y: -50%;
        transform: translate(0, -50%) rotate(0) skew(0) skewY(0) scaleX(1) scaleY(1);
        width: 1.5rem;
        height: 1.5rem;
        display: flex;
        top: 50%;
        right: .25rem;
        position: absolute;
        cursor: pointer;
        border: none;
    }
    .coupon-apply img ,.coupon-remove img,.coupon-btn img{
        width: 1.5rem;
        height: 1.5rem;

    }

    .coupon-btn{
        background-color: rgb(239 246 255 /1);
        border-radius: .375rem;
        justify-content: center;
        align-items: center;
        cursor: pointer;
        width: 2rem;
        height: 2rem;
        display: flex;
    }
    .method-label{
        line-height: 1.625;
        font-weight: 600;
        font-size: 1rem;
        padding: .5rem;
        border-color: transparent;
        border-width: 2px;
        gap: .5rem;
        justify-content: center;
        align-items: center;
        cursor: pointer;
        height: 3rem;
        display: flex;
    }
    .method-label.cash-method{
        background: #fbf9f9;
        color: rgb(45 212 191 / 1);
    }
    .method-label.card-method{
        background-color: rgb(219 234 254 /1);
        color: rgb(59 130 246/1);
    }
    .method-label.paypal-method{
        background-color: rgb(226 232 240 /1);
        color: rgb(49 46 129 /1);
    }
    .method-label.cheque-method{
        background-color: rgb(254 226 226 /1);
        color: rgb(248 113 113 /1);
    }
    .radio-method{
        position: absolute;
        width: 1px;
        height: 1px;
        padding: 0;
        margin: -1px;
        overflow: hidden;
        clip: rect(0, 0, 0, 0);
        white-space: nowrap;
        border-width: 0;
    }
    .radio-method:checked~.cash-method{
        border-color: rgb(45 212 191 / 1);
        border-width: 2px;
        border-style: solid;
    }
    .radio-method:checked~.card-method{
        border-color: rgb(59 130 246/1);
        border-width: 2px;
        border-style: solid;
    }
    .radio-method:checked~.method-label.paypal-method{
        border-color: rgb(49 46 129 /1);
        border-width: 2px;
        border-style: solid;
    }
    .radio-method:checked~.method-label.cheque-method{
        border-color: rgb(248 113 113 /1);
        border-width: 2px;
        border-style: solid;
    }
    .checout-btn{
        letter-spacing: -.025em;
        line-height: 1.25;
        font-weight: 500;
        font-size: 1rem;
        padding-left: 1rem;
        padding-right: 1rem;
        flex-grow: 1;
        height: 3rem;
        cursor: pointer;
        border:0;
    }
    .checout-btn.cancel{
        background-color: rgb(255 228 230 /1);
        color: rgb(220 38 38 /1);
    }
    .checout-btn.draft{
        background-color: rgb(252 211 77 /1);
        color: rgb(239 246 255 /1);
    }
    .checout-btn.complete{
        background-color: #864fe0;
        color: rgb(239 246 255 / 1);
    }
    .customScroll::-webkit-scrollbar {
        width: 5px !important;
    }

    .customScroll::-webkit-scrollbar-thumb {
        border-radius: 8px;
        background: #ddd;
    }
    /* customer modal */
    .customer-modal{
        overflow-y: auto;
        z-index: 10;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        position: fixed;
    }
    .customer-modal-container{
        padding-top: 1rem;
        padding-bottom: 5rem;
        padding-left: 1rem;
        padding-right: 1rem;
        min-height: 100vh;
    }
    @media (min-width: 640px) {
        .customer-modal-container{
            padding: 0;
            display: block;
        }
    }
    .customer-modal-shadow{
        --tw-bg-opacity: .75;
        background-color: rgb(107 114 128 / var(--tw-bg-opacity));
        transition-property: opacity;
        transition-timing-function: cubic-bezier(.4,0,.2,1);
        transition-duration: .15s;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        position: fixed;
    }
    .customer-modal-divider{
        display: none;
    }
    @media (min-width: 640px){
        .customer-modal-divider{
            vertical-align: middle;
            height: 100vh;
            display: inline-block;
        }
    }
    .customer-modal-content{
        transition-property: all;
        transition-timing-function: cubic-bezier(.4,0,.2,1);
        transition-duration: .15s;
        --tw-shadow: 0 20px 25px -5px rgb(0 0 0 / .1), 0 8px 10px -6px rgb(0 0 0 / .1);
        --tw-shadow-colored: 0 20px 25px -5px var(--tw-shadow-color), 0 8px 10px -6px var(--tw-shadow-color);
        box-shadow: 0 0 #0000, 0 0 #0000, var(--tw-shadow);
        vertical-align: bottom;
        text-align: left;
        background-color: rgb(255 255 255 / 1);
        border-radius: .5rem;
        overflow: hidden;
        transform: translate(0, 0) rotate(0) skew(0) skewY(0) scaleX(1) scaleY(1);
        display: inline-block;
    }

    .customer-modal-body{
        padding-top: 1.25rem;
        padding-bottom: 1rem;
        padding-left: 1rem;
        padding-right: 1rem;
        background-color: rgb(255 255 255 /1);
    }
    .customer-modal-title{
        color: rgb(17 24 39 /1);
        line-height: 1.5rem;
        font-weight: 500;
        font-size: 1.125rem;
    }
    .modal-input-container{
        padding-top: .75rem;
        padding-bottom: .75rem;
        gap: 1rem;
        width: 100%;
        display: grid;
    }
    .modal-input-label{
        color: rgb(100 116 139 /1);
    }
    .modal-input-field{
        background-color: rgb(248 250 252 /1);
        border-color: rgb(226 232 240 /1);
        border-radius: .5rem;
        width: 100%;
        margin-top: .5rem;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        border-width: 1px;
        padding: .5rem .75rem;
        font-size: 1rem;
        line-height: 1.5rem;
        border-style: solid;
    }
    .modal-input-select{
        color: rgb(148 163 184 /21);
        background-color: rgb(248 250 252 / 1);
        border-color: rgb(226 232 240 /1);
        border-radius: .5rem;
        width: 100%;
        margin-top: .5rem;
    }
    .select2-container{
        color: rgb(148 163 184 /21);
        background-color: rgb(248 250 252 / 1);
        border-color: rgb(226 232 240 /1);
        border-radius: .5rem;
        margin-top: .5rem;
    }
    .select2-container--bootstrap-5 .select2-selection{
        min-height: 42px;
    }
    .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered{
        line-height: 2;
    }
    .customer-modal-footer{
        padding-top: .75rem;
        padding-bottom: .75rem;
        padding-left: 1rem;
        padding-right: 1rem;
        background-color: rgb(249 250 251 / 1);
    }
    .customer-modal-btn{
        --tw-shadow: 0 1px 2px 0 rgb(0 0 0 / .05);
        --tw-shadow-colored: 0 1px 2px 0 var(--tw-shadow-color);
        box-shadow: var(--tw-ring-offset-shadow, 0 0 #0000), var(--tw-ring-shadow, 0 0 #0000), var(--tw-shadow);

        font-weight: 500;
        font-size: 1rem;
        line-height: 1.5rem;
        padding-top: .5rem;
        padding-bottom: .5rem;
        padding-left: 1rem;
        padding-right: 1rem;
        border-style: solid;
        border-color: transparent;
        border-width: 1px;
        border-radius: .375rem;
        justify-content: center;
        width: 100%;
        display: inline-flex;
    }
    .customer-modal-btn.close{
        color: rgb(55 65 81 /1);
        border-color: rgb(209 213 219 /1);
        background-color: rgb(255 255 255 /1);
    }
    .customer-modal-btn.submit{
        color: rgb(255 255 255 / 1);
        background-color: rgb(220 38 38 / 1);
    }
    .customer-modal-btn.submit:hover{
        background-color: rgb(185 28 28 / 1);
    }
    .customer-modal-btn.close:hover{
        background-color: rgb(249 250 251 /1);
    }
    @media (min-width: 640px) {
        .customer-modal-content{
            vertical-align: middle;
            max-width: 32rem;
            width: 100%;
            margin-top: 2rem;
            margin-bottom: 2rem;
        }
        .customer-modal-body{
            padding: 1.5rem;
            padding-bottom: 1rem;
        }
        .customer-modal-footer{
            padding-left: 1.5rem;
            padding-right: 1.5rem;
            flex-direction: row-reverse;
            display: flex;
        }
        .customer-modal-btn{
            font-size: .875rem;
            line-height: 1.25rem;
            width: auto;
            margin-left: .75rem;
        }
    }
    @media (min-width: 768px) {
        .modal-input-container{
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }



</style>
@endsection
@section('content')

<div class="p-2">
    <div class="row">
        <div class="col-md-3">
            <div class="category-section">
                <div class="d-flex">
                    <div class="cus-btn active cat-tab">Category</div>
                    <div class="cus-btn brand-tab">Brands</div>
                </div>
                <div class="cat-brand-list customScroll">
                    <div class="d-flex cat-tab-container" style="flex-wrap: wrap;gap:5px">
                        @foreach ($categories as $category)
                            <div class="cat-box category-select" data-id="{{$category->id}}">
                                {{$category->name}}
                            </div>
                        @endforeach
                    </div>
                    <div class="d-none brand-tab-container" style="flex-wrap: wrap;gap:5px">
                        @foreach ($brands as $brand)
                            <div class="cat-box brand-select" data-id="{{$brand->id}}">
                                {{$brand->name}}
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>
        <div class="col-md-4">
            <div class="product-section">
                <div class="section-title">
                    Products
                </div>
                <div class="search-container">
                    <input id="searchFeaturedProductInput" class="search-input" placeholder="Scan/Search featured product by name or code" type="text">
                    <button class="btn-search">
                        <img src="{{asset('public/images/barcode.svg')}}" alt="Icon">
                    </button>
                    <div id="searchFeaturedProducts" class="search-list" style="display: none;">

                    </div>
                </div>
                <div class="products-container customScroll">
                    {{-- <div class="product-list featuredProducts"> --}}
                    <div class="row featuredProducts m-0">
                        @foreach ($products as $product)
                        @if($product->variations->count())
                            @foreach ($product->variations as $variation)
                            @php
                                $v_product = $variation?->product;
                            @endphp
                            @if($v_product)
                            <div class="col-md-4" style="padding:2px">
                                <div class="product-box">
                                    <div class="product-img">
                                        <img src="{{$v_product->image_show}}" alt="{{$v_product->product_name}}">
                                    </div>
                                    <div class="product-content">
                                        <div class="product-name">{{$v_product->product_name}}</div>
                                        <div class="product-name">
                                            {!! $v_product->variation_attributes2 !!}
                                            {{-- {{$v_product->variation_attributes}} --}}
                                        </div>
                                        <div class="product-code">{{$v_product->product_code}}</div>
                                        @php
                                            $p_qty = $v_product->qty;
                                        @endphp
                                        <div class="product-stock">Stock : {{$p_qty}}</div>
                                    </div>
                                    <div class="product-hover">
                                        @if($p_qty > 0)
                                            <div class="add-cart-box">
                                                <button class="cart-btn"  onclick="minusProduct({{$v_product->id}})">
                                                    <img src="{{asset('public/images/minus.svg')}}" alt="">
                                                </button>
                                                <span class="cart-qty" id="featuredProducts_qty_{{$v_product->id}}">0</span>
                                                <button class="cart-btn" onclick="addProduct({{$v_product->id}},{{$p_qty}})">
                                                    <img src="{{asset('public/images/plus.svg')}}" alt="">
                                                </button>
                                            </div>
                                        @else
                                            <div class="stock-msg">
                                                Out of Stock
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endif
                            @endforeach
                        @else
                            <div class="col-md-4" style="padding:2px">
                                <div class="product-box">
                                    <div class="product-img">
                                        <img src="{{$product->image_show}}" alt="{{$product->product_name}}">
                                    </div>
                                    <div class="product-content">
                                        <div class="product-name">{{$product->product_name}}</div>
                                        <div class="product-code">{{$product->product_code}}</div>
                                        @php
                                            $p_qty = $product->qty;
                                        @endphp
                                        <div class="product-stock">Stock : {{$p_qty}}</div>
                                    </div>
                                    <div class="product-hover">
                                        @if($p_qty > 0)
                                            <div class="add-cart-box">
                                                <button class="cart-btn"  onclick="minusProduct({{$product->id}})">
                                                    <img src="{{asset('public/images/minus.svg')}}" alt="">
                                                </button>
                                                <span class="cart-qty" id="featuredProducts_qty_{{$product->id}}">0</span>
                                                <button class="cart-btn" onclick="addProduct({{$product->id}},{{$p_qty}})">
                                                    <img src="{{asset('public/images/plus.svg')}}" alt="">
                                                </button>
                                            </div>
                                        @else
                                            <div class="stock-msg">
                                                Out of Stock
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                        @endforeach

                    </div>
                    <nav aria-label="Page navigation example" id="p_pagination">
                    @if( $products->lastPage() > 1)
                    <!-- pagination-start -->

                        <ul class="pagination">
                            <li class="page-item">

                                <a class="page-link prev" href="#" page="1" aria-label="Previous">	<span aria-hidden="true">«</span>
                                </a>
                            </li>
                            @for($i=1;$i <= $products->lastPage();$i++)
                            <li class="page-item">
                                <a class="page-link page-link-{{$i}} {{ $i == $products->currentPage() ? 'active' : 0 }}" href="#" page="{{$i}}">{{ $i }} </a>
                            </li>
                            @endfor

                            <li class="page-item">

                                <a class="page-link next" last_page="{{$products->lastPage()}}" page="2" href="#" aria-label="Next">	<span aria-hidden="true">»</span>
                                </a>
                            </li>
                        </ul>

                    <!-- pagination-end -->
                    @endif
                    </nav>
                    {{-- {!! $products->links() !!} --}}
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="checkout-section">
                <div class="customer-box-container">
                    <div class="customer-inner-container">
                        <div class="customer-input-container">
                            <img class="customer-search-input-img" src="{{asset('public/images/user-tie-solid.svg')}}" alt="Icon">
                            <input type="text" data-id="0"  placeholder="Enter Customer name or phone number" id="searchCustomerInput" class="customer-search-input">
                        </div>
                        <button class="customer-add-btn" id="addCustomerBtn">
                            <img src="{{asset('public/images/plus.svg')}}" alt="icon">
                        </button>
                    </div>
                    <div id="searchCustomers" class="customer-search-list" style="display: none;">

                    </div>
                </div>
                <div class="product-cart-container customScroll">
                    <table class="table-container">
                        <thead class="table-header">
                            <tr>
                                <th class="text-left pl-4 talbe-header-th" style="width: 40px;" >Name</th>
                                {{-- <th class="text-center talbe-header-th">Code</th> --}}
                                {{-- <th class="text-center talbe-header-th">Tax</th> --}}
                                <th class="text-center talbe-header-th">Price</th>
                                <th class="text-center talbe-header-th">Qty</th>
                                <th class="text-right talbe-header-th" style="width: 6rem;">Subtotal</th>
                                <th style="width: 40px;"></th>
                            </tr>
                        </thead>
                        <tbody id="cart_products">
                            <tr id="noProducts" style="">
                                <td colspan="7"  style="border-color: #864fe0;text-align: center;border-bottom-width: 1px;height: 3rem;">
                                    No products available in the list
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="table-container">
                        <tfoot class="table-footer">
                            <tr>
                                <td class="checkout-cart-total-text" style="width: 50%;">Total Products:</td>
                                <td class="checkout-cart-total-amount">
                                    <span id="totalProduct">0</span>
                                    (
                                        <span id="totalItem">0</span>
                                    )
                                </td>
                                {{-- <td class="checkout-cart-total-del"></td> --}}
                            </tr>
                            <tr>
                                <td class="checkout-cart-total-text">Total Amount:</td>
                                <td class="checkout-cart-total-amount">
                                    <span id="totalAmount">$ 0.0</span>
                                </td>
                                {{-- <td class="checkout-cart-total-del"></td> --}}
                            </tr>
                            <tr>
                                <td class="checkout-cart-total-text">Tax:</td>
                                <td class="checkout-cart-total-amount">
                                    <span id="totalTax">$ 0.0</span>
                                </td>
                                {{-- <td class="checkout-cart-total-del"></td> --}}
                            </tr>
                            <tr>
                                <td class="checkout-cart-total-text">
                                    Discount:
                                </td>
                                <td class="checkout-cart-total-amount" style="color: rgb(251 113 133/1);">
                                    <span id="totalDiscount">$ 0.0</span>
                                </td>
                                {{-- <td class="checkout-cart-total-del"></td> --}}
                            </tr>
                            {{-- <tr>
                                <td colspan="5" class="checkout-cart-total-text">
                                    <div class="d-flex gap-2">
                                        <span>Coupon</span>
                                        <div class="position-relative">
                                            <input id="couponCodeInput" style="display: none;" type="text" class="coupon-input" placeholder="Coupon code..">
                                            <button style="display: none;" class="coupon-apply"  id="applyCouponBtn">
                                                <img style="width: 1.5rem;height: 1.5rem;" src="{{asset('public/images/checked.svg')}}" alt="icon">
                                            </button>
                                            <button style="display: none;" class="coupon-remove" id="removeCouponBtn">
                                                <img style="width: 1.5rem;height: 1.5rem;max-width: 100%;display: block;vertical-align: middle;" src="{{asset('public/images/removed.svg')}}" alt="icon">
                                            </button>
                                        </div>
                                        <buttton class="coupon-btn" id="addCouponBtn">
                                            <img src="{{asset('public/images/plus.svg')}}" alt="icon">
                                        </buttton>
                                    </div>
                                </td>
                                <td class="checkout-cart-total-amount">
                                    <span class="discount">$ 0.0</span>
                                </td>
                                <td class="checkout-cart-total-del"></td>
                            </tr> --}}
                            <tr>
                                <td style="background:#864fe0;padding-right: 0;padding: .5rem;">
                                    <div style="color: rgb(239 246 255 /1);font-weight: 700;font-size: 1rem;line-height: 1.5rem;justify-content: flex-end;width:100%;display:inline-flex;">
                                        <span>Grand Total :</span>
                                    </div>
                                </td>
                                <td style="background:#864fe0;text-align: right;padding: .5rem;">
                                    <div style="color: rgb(239 246 255 /1);font-weight: 700;font-size: 1rem;line-height: 1.5rem;">
                                        <span id="totalGrand" data-grand-price="0">$0.0</span>
                                    </div>
                                    <input type="hidden" name="grand_total_input" id="grand_total_input" value="0">
                                    <input type="hidden" name="total_discount_input" id="total_discount_input" value="0">
                                    <input type="hidden" name="total_tax_input" id="total_tax_input" value="0">
                                    <input type="hidden" name="total_qty_input" id="total_qty_input" value="0">
                                    <input type="hidden" name="total_sub_input" id="total_sub_input" value="0">
                                    <input type="hidden" name="total_sub_input_p" id="total_sub_input_p" value="0">
                                </td>
                                {{-- <td style="background:#864fe0;padding: .5rem;width: 3rem;height: 3rem;"></td> --}}
                            </tr>
                            <tr>
                                <td class="checkout-cart-total-text">
                                    Receive Amount:
                                </td>
                                <td class="checkout-cart-total-amount" style="color: rgb(251 113 133/1);">
                                    <div class="d-flex justify-content-end">
                                    <input type="number" id="receive_amount" value="0" class="form-control" style="width:250px;">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="checkout-cart-total-text">
                                    Change Amount:
                                </td>
                                <td class="checkout-cart-total-amount" style="color: rgb(251 113 133/1);">
                                    <div class="d-flex justify-content-end">
                                    <input disabled type="number" id="change_amount" value="0" class="form-control" style="width:250px;">
                                    </div>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                {{-- payment method --}}
                <div class="d-flex flex-wrap mt-3 gap-2">
                    @foreach ($methods as $k=>$method)
                    <div class="flex-grow-1 position-relative">
                        <input type="radio" @if($k == 0) checked @endif class="radio-method" name="method" data-account="{{$method->pos_account_id}}" value="{{$method->id}}" id="method_{{$method->id}}">
                        <label for="method_{{$method->id}}" class="method-label card-method">
                            <img style="width: 35px;height:35px;" src="{{$method->image_show}}" alt="icon">
                            <span>{{$method->name}}</span>
                        </label>
                    </div>
                    @endforeach
                    {{-- <div class="flex-grow-1 position-relative">
                        <input type="radio" class="radio-method" name="method" value="cash" id="cash" checked>
                        <label for="cash" class="method-label cash-method">
                            <img src="{{asset('public/images/cash.svg')}}" alt="icon">
                            <span>Cash</span>
                        </label>
                    </div>
                    <div class="flex-grow-1 position-relative">
                        <input type="radio" class="radio-method" name="method" value="card" id="card">
                        <label for="card" class="method-label card-method">
                            <img src="{{asset('public/images/card.svg')}}" alt="icon">
                            <span>Card</span>
                        </label>
                    </div>
                    <div class="flex-grow-1 position-relative">
                        <input type="radio" class="radio-method" name="method" value="paypal" id="paypal">
                        <label for="paypal" class="method-label paypal-method">
                            <img src="{{asset('public/images/paypal.svg')}}" alt="icon">
                            <span>Paypal</span>
                        </label>
                    </div>
                    <div class="flex-grow-1 position-relative">
                        <input type="radio" class="radio-method" name="method" value="cheque" id="cheque">
                        <label for="cheque" class="method-label cheque-method">
                            <img src="{{asset('public/images/cheque.svg')}}" alt="icon">
                            <span>Cheque</span>
                        </label>
                    </div> --}}
                </div>
                {{-- end payment method --}}
                {{-- action btn --}}
                <input type="hidden" id="saleId" value="">
                <div class="d-flex flex-wrap gap-2 mt-4">
                    <button class="checout-btn cancel" onclick="cancelSale()">Cancel</button>
                    <button class="checout-btn draft" onclick="complate('Draft')">Draft</button>
                    <button class="checout-btn complete" onclick="complate('Sales')">Save & Complete</button>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- customer modal --}}
<div class="invisible customer-modal"  id="customerAddModal">
    <div class="d-flex justify-content-center align-items-center text-center customer-modal-container">
        <div class="customer-modal-shadow"></div>
        <div class="customer-modal-divider"></div>
        <div class="customer-modal-content">
            <div class="customer-modal-body">
                <div class="d-sm-flex align-items-sm-start">
                    <div class="mt-3 text-center mt-sm-0 text-sm-start">
                        <h3 class="customer-modal-title">Add Customer</h3>
                        <div class="modal-input-container">
                            <div>
                                <label class="modal-input-label" for="name">Name<span style="color: rgb(239 68 68 /1);">*</span></label>
                                <input class="modal-input-field" type="text" placeholder="Enter Your Customer Name" id="name">
                            </div>
                            <div>
                                <label class="modal-input-label" for="email">Email<span style="color: rgb(239 68 68 /1);">*</span></label>
                                <input class="modal-input-field" type="text" placeholder="Enter Your Customer Email" id="email">
                            </div>
                            <div>
                                <label class="modal-input-label" for="mobile">Mobile<span style="color: rgb(239 68 68 /1);">*</span></label>
                                <input class="modal-input-field" type="text" placeholder="Enter Your Customer Mobile" id="mobile">
                            </div>
                            <div>
                                <label class="modal-input-label" for="country">Country<span style="color: rgb(239 68 68 /1);">*</span></label>
                                <select class="modal-input-select" name="country" id="country">

                                </select>
                            </div>
                            <div>
                                <label class="modal-input-label" for="state">State<span style="color: rgb(239 68 68 /1);">*</span></label>
                                <select class="modal-input-select" name="state" id="state">

                                </select>
                            </div>
                            <div>
                                <label class="modal-input-label" for="city">City<span style="color: rgb(239 68 68 /1);">*</span></label>
                                <select class="modal-input-select" name="city" id="city">

                                </select>
                            </div>
                            <div>
                                <label class="modal-input-label" for="zip_code">Zip Code<span style="color: rgb(239 68 68 /1);">*</span></label>
                                <input class="modal-input-field" type="text" placeholder="Enter Your Customer Zip Code" id="zip_code">
                            </div>
                            <div style="grid-column: span 2 / span 2;">
                                <label class="modal-input-label" for="address">Address<span style="color: rgb(239 68 68 /1);">*</span></label>
                                <input class="modal-input-field" type="text" placeholder="Enter Your Customer Address" id="address">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="customer-modal-footer">
                <button class="customer-modal-btn submit" id="submitCustomer">Submit</button>
                <button class="customer-modal-btn close"  id="closeModalCustomer">Close</button>
            </div>
        </div>
    </div>
</div>
{{-- end customer modal --}}
@endsection
@section('script')
<link rel="stylesheet" href="{{asset('public/assets/css/sweetalert2_n.min.css')}}">
<script src="{{asset('public/assets/js/sweetalert2.min.js')}}"></script>
<script>
    const Toast = Swal.mixin({
        toast: true,
        position: "bottom-end",
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener("mouseenter", Swal.stopTimer);
            toast.addEventListener("mouseleave", Swal.resumeTimer);
        },
    });
    $('.cus-btn').on('click',function(){

        if($(this).hasClass('active') == false){
            $('.cus-btn').removeClass('active');
            $(this).addClass('active');
            if($(this).hasClass('cat-tab')){
                $('.brand-tab-container').removeClass('d-flex').addClass('d-none');
                $('.cat-tab-container').removeClass('d-none').addClass('d-flex');
            }else{
                $('.cat-tab-container').removeClass('d-flex').addClass('d-none');
                $('.brand-tab-container').removeClass('d-none').addClass('d-flex');
            }
        }
    });
    var current_product_con="search";
    var current_product_con_val="";
    $(document).on('click', '.category-select', function(e) {
        var id = $(this).attr('data-id');
        current_product_con="category_id";
        current_product_con_val=id;
        $.ajax({
            url: '{{route("pos.product_search")}}',
            type: 'GET',
            data: {
                category_id: id
            },
            success: function(response) {
                $('.featuredProducts').html(response.data);
                $('#p_pagination').html(response.pagination);
            }
        });
    });
    $(document).on('click', '.brand-select', function(e) {
        var id = $(this).attr('data-id');
        current_product_con="brand_id";
        current_product_con_val=id;
        $.ajax({
            url: '{{route("pos.product_search")}}',
            type: 'GET',
            data: {
                brand_id: id
            },
            success: function(response) {
                $('.featuredProducts').html(response.data);
                $('#p_pagination').html(response.pagination);
            }
        });
    });
    $(document).on('click','a.page-link',function(){
        event.preventDefault();
        var page= $(this).attr('page');
        //console.log(page);
        $('.page-link').removeClass('active');
        $('.page-link-'+page).addClass('active');
        if(Number(page) > 1){
            $('.page-link.prev').attr('page',Number(page)-1);
        }else{
            $('.page-link.prev').attr('page',1);
        }
        var last_p = $('.page-link.next').attr('last_page');
        if(Number(last_p) > Number(page)){
            $('.page-link.next').attr('page',Number(page)+1);
        }else{
            $('.page-link.next').attr('page',last_p);
        }
        $.ajax({
            url: '{{route("pos.product_search")}}',
            type: 'GET',
            data: {
                current_product_con: current_product_con,
                current_product_con_val:current_product_con_val,
                page:page
            },
            success: function(response) {
                $('.featuredProducts').html(response.data);
            }
        });
    });
    $(document).ready(function() {
        // Debounce function definition
        function debounce(func, wait) {
            let timeout;
            return function(...args) {
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(this, args), wait);
            };
        }
        $('#searchFeaturedProductInput').on('keyup', debounce(function(e) {
            $('#searchFeaturedProducts').show().html('');
            var value = $(this).val().trim();
            var is_barcode = 0;
            if(value.indexOf('nbr') !== -1){
                is_barcode = 1;
            }

            $.ajax({
                url: '{{route("pos.product_search")}}',
                type: 'GET',
                data: {
                    search: value,
                    is_barcode:is_barcode
                },
                success: function(response) {

                    $('#searchFeaturedProducts').html(response.data);
                    // Automatic click if barcode length matches
                    if(is_barcode == 1){
                        if(response.barcode_res){
                            $('.product-select').first().trigger('click');
                        }
                    }

                },
                error: function(xhr, status, error) {
                    // Handle error
                    console.error(xhr.responseText);
                }
            });
        }, 300));
    });
    $('#searchCustomerInput').on('keyup', function(e) {
        $('#searchCustomers').show();
        var value = $(this).val();
        $.ajax({
            url: '{{route("pos.customer_search")}}',
            type: 'GET',
            data: {
                search: value
            },
            success: function(response) {
                $('#searchCustomers').html(response.data)
            },
            error: function(xhr, status, error) {
                // Handle error
                console.error(xhr.responseText);
            }
        });
    });
    $(document).on('click', '.customer-select', function(e) {
        var name = $(this).attr('data-name');
        var id = $(this).attr('data-id');
        $('#searchCustomerInput').val(name).attr('data-id', id);
    });
    $('#country').select2({
        theme: "bootstrap-5",
        placeholder:"Select a Country" ,
        allowClear: true,
        width:'100%',
        dropdownAutoWidth : true,
        containerCssClass: 'select-sm',
        dropdownParent:$( '#customerAddModal' ) ,
        ajax: {
            url:'{{route('select2.countries')}}' ,
            dataType: 'json',
            delay: 250,
            data: function (params) {
            return {
                value: $.trim(params.term),
            };
            },
            processResults: function (response) {
            return {
                results: response
            };
            },
            cache: true
        }
    });
    $('#state').select2({
        theme: "bootstrap-5",
        placeholder:"Select a State" ,
        allowClear: true,
        width:'100%',
        dropdownAutoWidth : true,
        containerCssClass: 'select-sm',
        dropdownParent:$( '#customerAddModal' ) ,
        ajax: {
            url:'{{route('select2.states.bycountry')}}' ,
            dataType: 'json',
            delay: 250,
            data: function (params) {
            return {
                country_id:$("#"+country).val(),
                value: $.trim(params.term),
            };
            },
            processResults: function (response) {
            return {
                results: response
            };
            },
            cache: true
        }
    });
    $('#city').select2({
        theme: "bootstrap-5",
        placeholder:"Select a City" ,
        allowClear: true,
        width:'100%',
        dropdownAutoWidth : true,
        containerCssClass: 'select-sm',
        dropdownParent:$( '#customerAddModal' ) ,
        ajax: {
            url:'{{route('select2.cities.byState')}}' ,
            dataType: 'json',
            delay: 250,
            data: function (params) {
            return {
                value: $.trim(params.term),
            };
            },
            processResults: function (response) {
            return {
                results: response
            };
            },
            cache: true
        }
    });
    $('#addCustomerBtn').on('click', function(e) {
        $('#customerAddModal').removeClass('invisible');
    });
    $('#closeModalCustomer').on('click', function(e) {
        $('#customerAddModal').addClass('invisible');
    });
    $(document).on('click', '#submitCustomer', function(e) {
        var customerGroup = $('#customer_group_id').find(":selected").val();
        var name = $('#name').val();
        var mobile = $('#mobile').val();
        var email = $('#email').val();
        var address = $('#address').val();
        var country = $('#country').val();
        var city = $('#city').val();
        var state = $('#state').val();
        var zip_code = $('#zip_code').val();
        $.ajax({
            url: '{{route("pos.add_customer")}}',
            type: 'GET',
            data: {
                name: name,
                mobile: mobile,
                email: email,
                address: address,
                country: country,
                city: city,
                state: state,
                zip_code: zip_code
            },
            success: function(response) {
                $('#name, #mobile, #email,#address, #country, #city, #state, #zip_code').val('');


                if(response.status == "errors"){

                }else if(response.status == 1){
                    $('#customerAddModal').addClass('invisible');
                    $('#searchCustomerInput').val(response.data.name).attr('data-id', response.data.id);
                    Toast.fire({
                        icon: 'success',
                        title: response.message
                    });

                }else{
                    $('#customerAddModal').addClass('invisible');
                    Toast.fire({
                        icon: 'success',
                        title: response.message
                    });
                }

            },
            error: function(xhr, status, error) {
                var response = JSON.parse(xhr.responseText);
                Toast.fire({
                    icon: 'error',
                    title: response.message
                })
            }
        })
    });
    $('body').on('click', function(e) {
        $('#searchFeaturedProductInput').val('');
        $('#searchFeaturedProducts').hide();
        $('#searchProducts').hide();
        $('#searchCustomers').hide();
    });
    $(document).on('click', '.search-product-select', function(e) {
        var id = $(this).attr('data-id');
        var stock = $(this).attr('data-stock');
        var qty = $('#productQty_' + id).val();
        console.log(qty)
        if (stock > 0 && (stock > qty || qty == undefined)) {
            productSelect(id);
        } else {
            Toast.fire({
                icon: 'error',
                title: "Out of Stock"
            })
        }
    });
    removeProductFromCart = (id) => {
        $(`#productSaleRow_${id}`).remove();
        const totalElement = document.getElementsByClassName('productSaleRow')
        if (totalElement.length == 0) {
            $('#noProducts').show();
        }
        $('#featuredProducts_qty_' + id).text(0);
        countQty();
    }
    minusProduct = (id) => {
        if (Number($('#productQty_' + id).val()) > 1)
            $('#productQty_' + id).val(Number($('#productQty_' + id).val()) - 1)

        $('#featuredProducts_qty_' + id).text(Number($('#productQty_' + id).val()));
        countQty();
    }
    addProduct = (id, stock) => {
        if (document.getElementById(`productQty_${id}`)) {
            if (Number($('#productQty_' + id).val()) < stock ) {
                $('#productQty_' + id).val(Number($('#productQty_' + id).val()) + 1);
                countQty();
            }
            // $('#featuredProductsborder_' + id).addClass('primary-boder-color');
            // $('#featuredProductsborderhover_' + id).removeClass('hidden');
            // $('#featuredProductsborderhover_' + id).addClass('flex');
            $('#featuredProducts_qty_' + id).text(Number($('#productQty_' + id).val()));
        } else {
            productSelect(id);
        }
    }
    function productSelect(id) {
        $('#noProducts').hide();
        $.ajax({
            url: '{{route("pos.product_details")}}',
            type: 'GET',
            data: {
                id: id
            },
            success: function(response) {
                console.log(response);
                var selectProduct = $(`#productSaleRow_${id}`);
                if (selectProduct.length) {
                    var qty = Number($(`#productQty_${id}`).val());
                    $(`#productQty_${id}`).val(qty + 1);
                } else {
                    $('#cart_products').append(response.data);
                    $('#featuredProducts_qty_' + id).text(Number($('#productQty_' + id).val()));
                }

                countQty();
            },
            error: function(xhr, status, error) {
                // Handle error
                console.error(xhr.responseText);
            }
        });
    }
    countQty = function() {
        let totalElement = document.getElementsByClassName('productSaleRow')
        var totalQty = 0;
        var totalTax = 0;
        var totalDiscount = 0;
        var grandSubtotal = 0;
        var grandPurchase = 0;
        for (var i = 0; i < totalElement.length; i++) {
            //console.log(totalElement[i].getElementsByClassName('productQty')[0]);
            var productQty = totalElement[i].getElementsByClassName('productQty')[0].value;
            var producTax = $(totalElement[i].getElementsByClassName('productQty')[0]).attr('data-tax');
            var producDiscount = $(totalElement[i].getElementsByClassName('productQty')[0]).attr('data-dis');
            var producPrice = $(totalElement[i].getElementsByClassName('productQty')[0]).attr('data-price');
            var p_Price = $(totalElement[i].getElementsByClassName('productQty')[0]).attr('data-purchase-price');

            // var productSubtotal = totalElement[i].getElementsByClassName('productSubtotal')[0]
            //     .getAttribute('data-subtotal');
            var subTotal = Number(productQty) * Number(producPrice);
            var subTotal_p = Number(productQty) * Number(p_Price);
            totalElement[i].getElementsByClassName('productSubtotal')[0].innerText = currencySymbol(subTotal);
            grandSubtotal = Number(grandSubtotal) + Number(subTotal.toFixed(2));
            grandPurchase = Number(grandPurchase) + Number(subTotal_p.toFixed(2));
            totalQty = Number(totalQty)+ Number(productQty);
            totalTax = Number(totalTax)+ Number(productQty) * Number(producTax);
            totalDiscount = Number(totalDiscount)+ Number(productQty) * Number(producDiscount);
            //var grandSubtotal = Number(grandSubtotal) + Number(totalElement[i].getElementsByClassName('productSubtotal')[0].innerText = subTotal.toFixed(2));

           // var totalQty = (Number(totalQty) + Number(totalElement[i].getElementsByClassName('productQty')[0].value));


        }
        $('#totalDiscount').html(currencySymbol(totalDiscount.toFixed(2)));
        $('#totalTax').html(currencySymbol(totalTax.toFixed(2)));
        // $('.discount').html('$ 0.00');
        $('#totalProduct').html(totalElement.length);
        $('#totalItem').html(totalQty);
        $('#totalAmount').html(currencySymbol(grandSubtotal.toFixed(2)));
        var grand_total = Number(grandSubtotal) + Number(totalTax) - Number(totalDiscount);
        $('#totalGrand').html(currencySymbol(grand_total.toFixed(2))).attr('data-grand-price', grand_total.toFixed(2));
        $('#grand_total_input').val(grand_total.toFixed(2));
        $('#total_sub_input').val(grandSubtotal.toFixed(2));
        $('#total_sub_input_p').val(grandPurchase.toFixed(2));
        $('#total_discount_input').val(totalDiscount.toFixed(2));
        $('#total_tax_input').val(totalTax.toFixed(2));
        $('#grand_qty_input').val(totalQty);
        $('#receive_amount').val(grand_total.toFixed(2));
        $('#change_amount').val(0);
    }
    $('#receive_amount').on('keyup',function(){
        var total = $('#grand_total_input').val();
        if(Number(total) < Number($(this).val())){
            $('#change_amount').val(Number($(this).val()) - Number(total));
        }
    });
    function currencySymbol(number){
        return '$ '+number;
    }

    $(document).on('click', '.productPriceCustomizeModal', function(e) {
        let id = $(this).attr('data-id');
        $('#productPriceCustomizationModal_' + id).removeClass('invisible');
    });
    $(document).on('click', '.closeProductPriceCustomizationModal', function(e) {
        let id = $(this).attr('data-id');
        $('#productPriceCustomizationModal_' + id).addClass('invisible');
    });
    $(document).on('click', '.submitProductPriceCustomizationModal', function(e) {
        var id = $(this).attr('data-id');
        var price = $('#productPriceCustomizationModal_' + id + ' input[name="price"]').val();

        var tax_type = $('#productPriceCustomizationModal_' + id + ' select[name="tax"] :selected').attr('rate_type');
        var tax_rate = $('#productPriceCustomizationModal_' + id + ' select[name="tax"] :selected').attr('rate');
       // console.log($(tax_option).attr('rate_type'));
        //var taxRate = $('#productPriceCustomizationModal_' + id + ' select[name="tax"]').val();
        var dis_type = $('#productPriceCustomizationModal_' + id + ' select[name="dis_type"]').val();
        var dis = $('#productPriceCustomizationModal_' + id + ' input[name="dis"]').val();
        var dis_rate = 0;
        if(dis_type == 'percent'){
            dis_rate = Number(price) * Number(dis)/100;
        }else{
            dis_rate = dis;
        }
        var taxRate = 0;
        if(tax_type == "Percentage"){
            taxRate = Number(price) * Number(tax_rate)/100;
        }else{
            taxRate = tax_rate;
        }
        // console.log(taxRate);
        $('#productQty_'+id).attr('data-tax',taxRate);
        $('#productQty_'+id).attr('data-dis',dis_rate);
        $('#productQty_'+id).attr('data-price',price);
        $('#productPrice_' + id).text(currencySymbol(Number(price)));
        // var taxRate = $('#productPriceCustomizationModal_' + id + ' input[name="price"]').attr(
        //     'data-tax-rate');
        // var tax = taxRate ?? 0;
        // if (tax > 0) {
        //     tax = Number(price) * Number(taxRate) / 100;
        // }
        // var subtotal = Number(price) + Number(tax);
        // var qty = $('#productQty_' + id).val();
        // $('#productTax_' + id).text(currencySymbol(Number(tax)));
        // $('#productPrice_' + id).text(currencySymbol(Number(price))).attr('data-price', Number(
        //     price));
        // $('#productSubtotal_' + id).text(currencySymbol(Number(subtotal) * Number(qty))).attr(
        //     'data-subtotal', subtotal);
         $('#productPriceCustomizationModal_' + id).addClass('invisible');
        countQty();
    });

    $(document).on('click', '#addCouponBtn', function(e) {
        $('#couponCodeInput').show();
        $('#removeCouponBtn').show();
        $('#addCouponBtn').hide();
    });
    $(document).on('click', '#removeCouponBtn', function(e) {
        $('#couponCodeInput').hide();
        $('#removeCouponBtn').hide();
        $('#addCouponBtn').show();
    });
    $(document).on('keyup', '#couponCodeInput', function(e) {
        var value = $(this).val();
        if (value == '') {
            $('#applyCouponBtn').hide();
            $('#removeCouponBtn').show();
        } else {
            $('#applyCouponBtn').show();
            $('#removeCouponBtn').hide();
        }
    });
    $(document).on('click', '#applyCouponBtn', function(e) {
        var value = $('#couponCodeInput').val();
        var price = $('#totalGrand').attr('data-grand-price');
        console.log(price)
        if (price == 0) {
            Toast.fire({
                icon: 'error',
                title: "No Product Selected"
            })
        }
        if (value && price > 0) {
            $.ajax({
                url: '/coupon/apply',
                type: 'GET',
                data: {
                    code: value,
                    price: price
                },
                success: function(response) {
                    $('#couponCodeInput').hide();
                    $('#removeCouponBtn').hide();
                    $('#applyCouponBtn').hide();
                    $('#addCouponBtn').show();

                    $('#totalDiscount').html(currencySymbol(response.data.discount));
                    $('.discount').html(currencySymbol(response.data.discount)).attr(
                        'data-coupon-id',
                        response.data.id);
                    const grandTotal = $('#totalGrand').attr('data-grand-price');
                    const newGrandTotal = Number(grandTotal) - Number(response.data
                        .discount);
                    $('#totalGrand').html(currencySymbol(newGrandTotal)).attr(
                        'data-grand-price',
                        newGrandTotal);
                    Toast.fire({
                        icon: 'success',
                        title: response.message
                    });
                },
                error: function(xhr, status, error) {
                    var response = JSON.parse(xhr.responseText);
                    Toast.fire({
                        icon: 'error',
                        title: response.message
                    })
                }
            });
        }
    });

    cancelSale = () => {
        let totalElement = document.getElementsByClassName('productSaleRow');
        for (var i = 0; i < totalElement.length; i++) {
            var productId = totalElement[i].getAttribute('data-id');
            // $('#featuredProductsborder_' + productId).removeClass('primary-boder-color');
            // $('#featuredProductsborderhover_' + productId).addClass('hidden');
            // $('#featuredProductsborderhover_' + productId).removeClass('flex');
            $('#featuredProducts_qty_' + productId).text(0);
        }
        $('.productSaleRow').remove();
        $('#noProducts').show();
        countQty();
    }
    complate = (type) => {
            const totalGrand = $('#totalGrand').attr('data-grand-price');
            const customer_id = $('#searchCustomerInput').attr('data-id');
            // const coupon_id = $('.discount').attr('data-coupon-id');
            const payment_method = $('input[name="method"]:checked').val();
            var formData = new FormData;
            let totalElement = document.getElementsByClassName('productSaleRow');
            var total_g = $('#grand_total_input').val();
            var total_r = $('#receive_amount').val();
            if(Number(total_g) > Number(total_r)){
                Toast.fire({
                    icon: 'error',
                    title: "Receiving Amount is smaller than grand total"
                })
            }

            for (var i = 0; i < totalElement.length; i++) {
                var productQty = totalElement[i].getElementsByClassName('productQty')[0].value;
                var producTax = $(totalElement[i].getElementsByClassName('productQty')[0]).attr('data-tax');
                var product_id = $(totalElement[i].getElementsByClassName('productQty')[0]).attr('data-id');
                var producDiscount = $(totalElement[i].getElementsByClassName('productQty')[0]).attr('data-dis');
                var producPurchasePrice = $(totalElement[i].getElementsByClassName('productQty')[0]).attr('data-purchase-price');
                var producPrice = $(totalElement[i].getElementsByClassName('productQty')[0]).attr('data-price');
                var producUnit = $(totalElement[i].getElementsByClassName('productQty')[0]).attr('data-unit');
                formData.append('qty['+product_id+']', productQty);
                formData.append('tax['+product_id+']', producTax);
                formData.append('discount['+product_id+']', producDiscount);
                formData.append('price['+product_id+']', producPrice);
                formData.append('purchase_price['+product_id+']', producPurchasePrice);
                formData.append('unit['+product_id+']', producUnit);
               // formData.append('unit['+product_id+']',  $('#searchCustomerInput').attr(''));

                // var productQty = totalElement[i].getElementsByClassName('productQty')[0].value;
                // var productPrice = totalElement[i].getElementsByClassName('productPrice')[0].getAttribute(
                //     'data-price');
                // var productId = totalElement[i].getAttribute('data-id');
                // if(totalElement[i].getElementsByClassName('productVariant')[0]) {
                //     var productVariant = totalElement[i].getElementsByClassName('productVariant')[0].value;
                // }
                // if(totalElement[i].getElementsByClassName('productSerialNumber')[0]) {
                //     var productSerialNumber = totalElement[i].getElementsByClassName('productSerialNumber')[0].value;
                // }

                // qtyArray.push(Number(productQty));
                // ProductIdArray.push(Number(productId));
                // ProductPriceArray.push(Number(productPrice));
                // ProductVariantArray.push(productVariant || null);
                // productSerialNumberArray.push(productSerialNumber || null);

                // $('#featuredProductsborder_' + productId).removeClass('primary-boder-color');
                // $('#featuredProductsborderhover_' + productId).addClass('hidden');
                // $('#featuredProductsborderhover_' + productId).removeClass('flex');
                $('#featuredProducts_qty_' + product_id).text(0);

            }

            if (totalElement.length == 0) {
                Toast.fire({
                    icon: 'error',
                    title: "No Product Selected"
                })
            } else {

                formData.append('type',type);
                formData.append('_token',"{{ csrf_token() }}");
                formData.append('change_amount', $('#change_amount').val());
                formData.append('receive_amount', $('#receive_amount').val());
                formData.append('grand_total', $('#grand_total_input').val());
                formData.append('sub_total', $('#total_sub_input').val());
                formData.append('sub_total_p', $('#total_sub_input_p').val());
                formData.append('total_qty', $('#total_qty_input').val());
                formData.append('total_tax', $('#total_tax_input').val());
                formData.append('total_discount', $('#total_discount_input').val());
                formData.append('payment_method', $('input[name="method"]:checked').val());
                formData.append('customer_id', customer_id);
                formData.append('account', $('input[name="method"]:checked').attr('data-account'));
                $.ajax({
                    url: '{{route("pos.sale")}}',
                    type: 'POST',
                    data:formData,
                    //dataType:'json',
                    processData: false,
                    contentType: false,
                    cache: false,
                    enctype: 'multipart/form-data',
                    // data: {
                    //     sale_id: $('#saleId').val(),
                    //     type: type,
                    //     paid_amount: totalGrand,
                    //     qty: qtyArray,
                    //     price: ProductPriceArray,
                    //     product_ids: ProductIdArray,
                    //     product_variant_ids: ProductVariantArray,
                    //     product_serial_numbers: productSerialNumberArray,
                    //     customer_id: customer_id,
                    //     // coupon_id: coupon_id,
                    //     payment_method: payment_method,
                    // },
                    success: function(response) {
                        console.log(response);
                        if(response.status == "success"){
                            $('#saleId').val(response.data.id);
                            Toast.fire({
                                icon: 'success',
                                title: response.message
                            });
                            cancelSale();
                            if (type == 'Sales') {
                                var newWin=window.open('','Print-Window');
                                newWin.document.open();
                                newWin.document.write(response.invoice);
                                newWin.document.close();
                                setTimeout(function(){newWin.close();},500);
                                $('#searchFeaturedProductInput').focus();
                                //window.location = '{{url("pos-sale/invoice")}}' + response.data.id;
                            }
                        }else{
                            Toast.fire({
                                icon: 'success',
                                title: response.message
                            });
                        }

                    },
                    error: function(xhr, status, error) {

                        var response = JSON.parse(xhr.responseText);
                        Toast.fire({
                            icon: 'error',
                            title: response.message
                        })
                    }
                });
            }

        }
</script>
@endsection
