/*
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the
 * Free Software Foundation, Inc.,
 * 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */
package org.rtt.demo.service;

import java.sql.ResultSet;

import java.sql.SQLException;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Date;
import java.util.HashMap;
import java.util.List;
import java.util.Locale;
import java.util.Map;
import javax.sql.DataSource;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.dao.DataAccessException;
import org.springframework.jdbc.core.simple.ParameterizedBeanPropertyRowMapper;
import org.springframework.jdbc.core.simple.SimpleJdbcInsert;
import org.springframework.jmx.export.annotation.ManagedOperation;
import org.springframework.security.authentication.encoding.Md5PasswordEncoder;
import org.springframework.security.authentication.encoding.PasswordEncoder;
import org.springframework.transaction.annotation.Transactional;
import org.springframework.jdbc.core.JdbcTemplate;
import org.springframework.jdbc.core.RowMapper;
import org.apache.log4j.Logger;
import org.rtt.demo.domain.DemoUser;

/**
 *
 * @author davidwaf
 */
public class UserServiceImpl implements UserService {

    private SimpleJdbcInsert insertDemoUser;
    private SimpleJdbcInsert insertRole;
    private JdbcTemplate jdbcTemplate;
    private final List<DemoUser> DemoUsers = new ArrayList<DemoUser>();
    protected static Logger logger = Logger.getLogger("controller");
    protected static SimpleDateFormat dateFormatter = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss", new Locale("en_US"));

    @Autowired
    public void init(DataSource dataSource) {
        this.jdbcTemplate = new JdbcTemplate(dataSource);
        this.insertDemoUser = new SimpleJdbcInsert(dataSource).withTableName("users");
        this.insertRole = new SimpleJdbcInsert(dataSource).withTableName("authorities");

    }

    @ManagedOperation
    @Transactional(readOnly = true)
    public void refreshDemoUsersCache() throws DataAccessException {
        synchronized (this.DemoUsers) {

            // Retrieve the list of all vets.
            this.DemoUsers.clear();
            this.DemoUsers.addAll(this.jdbcTemplate.query(
                    "SELECT * FROM DemoUsers WHERE deleted = 'no' ORDER BY lastname,firstname",
                    ParameterizedBeanPropertyRowMapper.newInstance(DemoUser.class)));
        }
    }

   

    @Override
    public void addRestDemoUser(String username, String password) {
        Map<String, Object> parameters = new HashMap<String, Object>();
        parameters.put("username", username);
        parameters.put("password", encodePassword(password));
        parameters.put("firstname", "rest");
        parameters.put("lastname", "rest");
        parameters.put("emailaddress", "rest");

        parameters.put("deleted", "no");
        parameters.put("enabled", 1);
        insertDemoUser.execute(parameters);
        addRole(username, "ROLE_USER");
    }

    
    @Override
    public void removeStaleRestDemoUsers() {

        List<DemoUser> demoUsers = this.jdbcTemplate.query(
                "SELECT * FROM users where firstname = 'rest' and lastname= 'rest'",
                new DemoUserMapper());
        Date now = new Date();

        for (DemoUser demoUser : demoUsers) {
            String username = demoUser.getUsername();
            try {
                Date dateCreated = dateFormatter.parse(demoUser.getDateCreated());
                long diffInMilliSeconds = now.getTime() - dateCreated.getTime();
                long seconds = diffInMilliSeconds / 1000;
                long minutes = seconds / 60;
                long hours = minutes / 60;
                if (hours > 5) {
                    deleteRestDemoUser(username);
                }
            } catch (Exception ex) {
                deleteRestDemoUser(username);
            }

        }

    }


    

    private void deleteRestDemoUser(String username) {
        String sql1 =
                "delete from rttdemo_jnlp where DemoUsername = '" + username + "'";
        String sql2 =
                "delete from users where username = '" + username + "'";
        String sql3 =
                "delete from authorities where username = '" + username + "'";
        String[] sqls = {sql1,sql2, sql3};
        this.jdbcTemplate.batchUpdate(sqls);

    }

    private void deleteExpiredDemoUser(String demoUsername) {
       String sql2 =
                "delete from users where username = '" + demoUsername + "'";
        String sql3 =
                "delete from authorities where username = '" + demoUsername + "'";
        String[] sqls = {sql2, sql3};
        this.jdbcTemplate.batchUpdate(sqls);

    }
 private void addRole(String username, String role) {
        Map<String, Object> parameters = new HashMap<String, Object>();
        parameters.put("username", username);
        parameters.put("authority", role);
        insertRole.execute(parameters);
    }
    private String encodePassword(String plainPassword) {
        PasswordEncoder encoder = new Md5PasswordEncoder();
        return encoder.encodePassword(plainPassword, null);
    }

    private static final class DemoUserMapper implements RowMapper<DemoUser> {

        @Override
        public DemoUser mapRow(ResultSet rs, int rowNum) throws SQLException {
            DemoUser demoUser = new DemoUser();
            String nickName=rs.getString("firstname")+" "+rs.getString("lastname");
            demoUser.setUsername(rs.getString("username"));
            demoUser.setNickName(nickName);
            return demoUser;
        }
    }

}
