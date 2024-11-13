describe('Replacement of term in user interface', function () {
	before(function() {
        Cypress.config('defaultCommandTimeout', 4000);
    });
    it('Checks presence of terms to be replaced in public site', function () {
        cy.visit('');

        cy.contains('Latest preprints');
        cy.contains('a', 'Archives').click();
        cy.contains('a', 'Finocchiaro: Arguments About Arguments').click();

        cy.get('.cmp_breadcrumbs').within(() => {
            cy.contains('span', 'Home');
            cy.contains('span', 'Preprints');
        });

        cy.get('.obj_preprint_details').within(() => {
            cy.contains('span', 'Preprint');
            cy.contains('span', 'Version 1');
        });
	});
});
