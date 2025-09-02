{{-- Estilos específicos para os modais --}}
<style>
.rating-container .radio {
    margin-bottom: 15px;
}
.rating-container .radio label {
    cursor: pointer;
    padding: 10px 15px;
    border-radius: 8px;
    transition: all 0.2s;
    display: block;
    border: 2px solid #e9ecef;
    background-color: #f8f9fa;
}
.rating-container .radio label:hover {
    border-color: #ffc107;
    background-color: #fff3cd;
}
.rating-container .radio input[type="radio"]:checked + label,
.rating-container .radio input[type="radio"]:checked ~ label {
    background-color: #ffc107;
    border-color: #ffc107;
    color: #212529;
    font-weight: bold;
}
.rating-container .radio input[type="radio"]:checked + label i,
.rating-container .radio input[type="radio"]:checked ~ label i {
    color: #ffffff !important;
}
.rating-container .radio input[type="radio"] {
    margin-right: 10px;
}
.rating-container i.fa-chamado {
    font-size: 24px;
    margin-right: 10px;
    transition: color 0.2s;
}
.rating-container img {
    width: 24px;
    height: 24px;
    margin-right: 10px;
}
.rating-container p {
    margin: 5px 0 0 0;
    font-size: 14px;
    display: inline;
}

/* Estilos para ícones de avaliação */
.fa-2 { font-size: 2em; }
.fa-3 { font-size: 4em; }
.fa-4 { font-size: 7em; }
.fa-5 { font-size: 12em; }
.fa-6 { font-size: 20em; }
.fa-chamado { font-size: 10.5em; }
</style>
