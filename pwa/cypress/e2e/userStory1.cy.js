describe('User story 1', () => {
    before(() => {
        cy.fixture('/response/courses.json').then((courses) => {
            cy.fixture('/response/comments.json').then((comments) => {
                cy.fixture('/response/formers.json').then((formers) => {
                    cy.fixture('/response/user_courses.json').then((user_courses) => {
                        cy.fixture('/response/users.json').then((users) => {
                            cy.intercept('GET', 'https://localhost/courses', {
                                statusCode: 200,
                                body: courses
                            }).as('getCourses');
                            cy.intercept('GET', 'https://localhost/comments', {
                                statusCode: 200,
                                body: comments
                            }).as('getComments');
                            cy.intercept('GET', 'https://localhost/formers', {
                                statusCode: 200,
                                body: formers
                            }).as('getFormers');
                            cy.intercept('GET', 'https://localhost/user_courses', {
                                statusCode: 200,
                                body: user_courses
                            }).as('getUserCourses');
                            cy.intercept('GET', 'https://localhost/users', {
                                statusCode: 200,
                                body: users
                            }).as('getUsers');
                            cy.clearLocalStorage()
                            cy.visit('/');
                            cy.wait('@getCourses')
                            cy.wait('@getComments')
                            cy.wait('@getFormers')
                            cy.wait('@getUserCourses')
                            cy.wait('@getUsers')
                        });
                    });
                });
            });
        })
    });
    it('should login user', () => {
        cy.fixture('/response/login/user.json').then((userLoggedIn) => {
            cy.fixture('/login/userTrue.json').then((userTrue) => {
                cy.fixture('/response/formers.json').then((formers) => {
                    cy.fixture('/response/buy/buy.json').then((cartResponse) => {
                      cy.fixture('/response/login/getUser.json').then((getUser) => {
                        cy.fixture('/response/user_courses_after_buy.json').then((userCoursesAfterBuy) => {
                          cy.intercept('POST', 'https://localhost/auth', {
                            statusCode: 200,
                            body: userLoggedIn
                          }).as('getUserLoggedIn');
                          cy.intercept('POST', 'https://localhost/course/buy', {
                            statusCode: 200,
                            body: cartResponse,
                            headers: {
                              Authorization: 'Bearer '+userLoggedIn.token
                            }
                          }).as('getCartResponse');
                          cy.intercept('GET', 'https://localhost/formers', {
                            statusCode: 200,
                            body: formers
                          }).as('getFormers');
                          cy.intercept('GET', 'https://localhost/users/'+userLoggedIn.user_id, {
                            statusCode: 200,
                            body: getUser
                          }).as('getUserInformations')
                          cy.intercept('GET', 'https://localhost/user_courses', {
                            statusCode: 200,
                            body: userCoursesAfterBuy
                          }).as('getUserCoursesAfterBuy')
                          cy.get('.bttn-login').click();
                          cy.wait(500)
                          cy.get('#mail').type(userTrue.mail)
                          cy.get('#password').type(userTrue.password)
                          cy.get('.bttn-submit').click();
                          cy.wait('@getUserLoggedIn')
                          cy.get('[data-test="allCoursesMenu"]').click()
                          cy.get('[data-test="addToCartButton"]').first().click();
                          cy.get('[data-test="cartButton"]').click()
                          cy.get('.title-item').should('be.visible')
                          cy.get('[data-test="submitCartButton"]').click();
                          cy.wait('@getCartResponse')
                          cy.wait('@getUserInformations')
                          cy.wait('@getUserCoursesAfterBuy')
                          cy.clearLocalStorage('CART')
                          cy.get('[data-test="myPurchases"]').click()
                          cy.get('.wrapper-item').should('be.visible')
                          cy.get('.wrapper-item').click()
                          cy.get('[data-test="continueCourse"]').click()
                        })
                      })
                    })
                })
            })
        })
    });
});
