<?php

class NoShopYet {

    public function __construct($href) {
        $this->href = $href;
    }

    public function render() {
        echo '
            <div class="container h-100">
                <div class="text-white bg-orange border rounded border-0 p-4 py-5">
                    <div class="row h-100">
                        <div class="col-md-10 col-xl-8 text-center d-flex d-sm-flex d-md-flex justify-content-center align-items-center mx-auto justify-content-md-start align-items-md-center justify-content-xl-center">
                            <div>
                                <h1 class="text-uppercase fw-bold text-white mb-3">There is no shop yet</h1>
                                <p class="mb-4">Improve your business with Geeco, open your ecommerce and start selling your products.<br></p>
                                <button class="btn btn-white fs-5 py-2 px-4" type="button" onclick="window.location.href= \'' . $this->href . '\'">Open your shop</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        ';
    }

}

?>