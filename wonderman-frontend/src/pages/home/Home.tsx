import React, {useEffect, useState} from 'react';
import Wrapper from "../../components/Wrapper/Wrapper";
import Carousel from "../../components/Carousel/Carousel";
import axios from "axios";
import {headers} from "../../axios/commons";
import {Product} from "../../models/Product";
import {Link} from "react-router-dom";

const Home = () => {
    const [products, setProducts] = useState([]);

    useEffect(() => {
        axios.get("products/bests", {headers: headers()})
            .then(response => {
                setProducts(response.data);
            })
    }, []);


    return (
        <Wrapper>
            <div className="home-page">
                <Carousel/>

                <h2>Our bestsellers</h2>
                <div className="products">

                    {products?.map((product: Product) => {
                        return (
                            <Link to={"/products/" + product.id}>
                                <div className="product-card">

                                    <div className="img">
                                        <img src={product.photo} alt="Photo."/>
                                    </div>


                                    <div className="info">
                                        <h2>{product.name}</h2>
                                        <div className="info-section"><strong>{product.netto} $</strong></div>
                                        <div className="info-section">{product.description.slice(0, 100)}...</div>
                                    </div>

                                </div>
                            </Link>
                        )
                    })}

                </div>

            </div>
        </Wrapper>
    );
};

export default Home;