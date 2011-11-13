<%@ taglib prefix="c" uri="http://java.sun.com/jsp/jstl/core" %>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <body>
        <div id="main">
            <div id="header">
                <table class="headerbg" width="980">
                    <tr>
                        <td align="left" width="70%"> <a href=""><div id="logo"></div></a></td>
                        <td align="right" width="30%">
                            <div id="nav-menu">
                                <ul>
                                    <li><a href="<%= request.getContextPath()%>/help">Help</a><!-- /resources/help/rttdemo.pdf" -->
                                    <c:choose>
                                        <c:when test="${not empty help}">
                                            <li><a href="<%= request.getContextPath()%>/help/uploadfile/student">Student</a>
                                            <li><a href="<%= request.getContextPath()%>/help/uploadfile/lecturer">Lecturer</a>
                                        </c:when>
                                    </c:choose>
                                    <sec:authorize access="hasRole('ROLE_ADMIN')">
                                            <li><a href="<%= request.getContextPath()%>/admin">Admin</a>
                                    </sec:authorize>
                                    <c:choose>
                                        <c:when test="${not empty loginname}">
                                            <li><a href="<%= request.getContextPath()%>/classlist">Home</a>
                                            <li><a href="<%= request.getContextPath()%>/j_spring_security_logout">Sign Out</a>
                                        </c:when>
                                    </c:choose>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <c:choose>
                        <c:when test="${not empty loginname}">
                            <tr>
                                <td align="left" colspan="2">
                                    <div id="prelogintoolbar">
                                    <!-- <h1>You are logged in as ${loginname}</h1>-->
                                    </div>
                                </td>
                            </tr>
                        </c:when>
                    </c:choose>
                </table>
            </div>
