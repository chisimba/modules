<%@ include file="/WEB-INF/jsp/includes.jsp" %>
<%@ include file="/WEB-INF/jsp/header.jsp" %>


<form:form method="post" id="rttdemoform" commandName="demoUser">
    <div id="meetings">
        <h1>To test drive demo, enter nickname:</h1>
    </div>
    <div id="indexmain">
        <fieldset>
            <table width="100%">
                <tr>
                    <td ><label>*&nbsp;Nick Name:</label></td>
                </tr>
                <tr>
                    <td><form:input path="nickName" /></td>
                </tr>
                <tr>
                    <td><form:errors path="nickName" cssClass="error" /></td>

                </tr>


                <tr>
                    <td>
                        <a id="save" class="sexybutton sexysilver" href="#;"><span><span>Go</span></span></a>

                    </td>
                </tr>

            </table>
        </fieldset>
    </div>

</form:form>

<%@ include file="/WEB-INF/jsp/footer.jsp" %>

<script>
   
    
    $(document).ready(function() {
       
        $('#save').click(function() {
            $('#rttdemoform').submit();
        });
        
      
    });
</script>