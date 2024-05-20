<!-- Modal -->
<div class="modal fade" id="glassesModal" tabindex="-1" aria-labelledby="glassesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="glassesModalLabel">Brillen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Brillen in einer gleichmäßigen Höhe nebeneinander -->
                <div class="row row-cols-1 row-cols-md-3 g-4">
                    <div class="col">
                        <div class="card h-100">
                            <img src="<?php echo home_url('/wp-content/uploads/2024/05/rote-brille.jpeg'); ?>" class="card-img-top" alt="Brille 1">
                            <div class="card-body">
                                <h5 class="card-title">Crimson Circular</h5>
                                <p class="card-text">Preis: 100 €</p>
                                <p class="card-text">Material: Plastik</p>
                                <p class="card-text">Gewicht: leicht</p>
                                <p class="card-text">Besonders Robust</p>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card h-100">
                            <img src="<?php echo home_url('/wp-content/uploads/2024/05/randlos.jpeg'); ?>" class="card-img-top" alt="Brille 2">
                            <div class="card-body">
                                <h5 class="card-title">Crystal Edge Randlos</h5>
                                <p class="card-text">Preis: 120 €</p>
                                <p class="card-text">Material: Edelstahl</p>
                                <p class="card-text">Gewicht: Schwer</p>
                                <p class="card-text">Gefertigt in Deutschland</p>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card h-100">
                            <img src="<?php echo home_url('/wp-content/uploads/2024/05/blau-eckig.jpeg'); ?>" class="card-img-top" alt="Brille 3">
                            <div class="card-body">
                                <h5 class="card-title">Sapphire Square</h5>
                                <p class="card-text">Preis: 150 €</p>
                                <p class="card-text">Material: Plastik</p>
                                <p class="card-text">Gewicht: Mittel</p>
                                <p class="card-text">Der Allrounder</p>
                            </div>
                        </div>
                    </div>
                    <!-- Weitere Brillen können hier hinzugefügt werden -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Schließen</button>
            </div>
        </div>
    </div>
</div>
