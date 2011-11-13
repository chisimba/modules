<%@ include file="/WEB-INF/jsp/includes.jsp" %>
<%@ include file="/WEB-INF/jsp/header.jsp" %>
<div id="login">
    <div id="stylized" class="myform">

        <h1>Login</h1>

        <div class="error">${error}</div>

        <form name="f" action="<c:url value='j_spring_security_check'/>" method="POST">
            <table>
                <tr><td>Student/Staff number:</td><td><input type='text' name='j_username' value='<c:if test="${not empty param.login_error}"><c:out value="${SPRING_SECURITY_LAST_USERNAME}"/></c:if>'/></td></tr>
                <tr><td>Password:</td><td><input type='password' name='j_password'></td></tr>

                <!--<tr><td><input type="checkbox" name="_spring_security_remember_me"></td><td>Don't ask for my password for two weeks</td></tr-->

                <tr><td colspan='2'><input name="submit" type="submit" value="Login"></td></tr>

            </table>

        </form>
    </div>
</div>
<%@ include file="/WEB-INF/jsp/footer.jsp" %>
