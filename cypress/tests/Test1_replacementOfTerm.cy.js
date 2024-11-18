describe('Replacement of term in user interface', function () {
    it('Checks presence of terms to be replaced in public site', function () {
        cy.visit('');

        cy.contains('Latest preprints');
        cy.contains('a', 'Archives').click();
        cy.contains('a', 'Finocchiaro: Arguments About Arguments').click();

        cy.get('.cmp_breadcrumbs').within(() => {
            cy.contains('a', 'Home');
            cy.contains('span', 'Preprints');
        });

        cy.get('.obj_preprint_details').within(() => {
            cy.contains('span', 'Preprint');
            cy.contains('span', 'Version 1');
        });
	});
    it('Enables plugin back again', function () {
        cy.login('dbarnes', null, 'publicknowledge');
		cy.contains('a', 'Website').click();

		cy.waitJQuery();
		cy.get('#plugins-button').click();

		cy.get('input[id^=select-cell-userinterfacetermreplacementplugin]').check();
		cy.get('input[id^=select-cell-userinterfacetermreplacementplugin]').should('be.checked');
    });
    it('Checks presence of terms to be replaced in public site', function () {
        cy.visit('');

        cy.contains('Latest postprints');
        cy.contains('a', 'Archives').click();
        cy.contains('a', 'Finocchiaro: Arguments About Arguments').click();

        cy.get('.cmp_breadcrumbs').within(() => {
            cy.contains('a', 'Home');
            cy.contains('span', 'Postprints');
        });

        cy.get('.obj_preprint_details').within(() => {
            cy.contains('span', 'Postprint');
            cy.contains('span', 'Version 1');
        });
	});
});
